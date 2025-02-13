<?php

namespace App\Http\Controllers;

use App\Http\Enums\TransactionCategory;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\StoreActivePlanRequest;
use App\Http\Requests\UpdateActivePlanRequest;
use App\Http\Resources\ActivePlanResource;
use App\Http\Traits\HttpResponses;
use App\Models\ActivePlan;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\Sale;
use App\Models\Transaction;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class ActivePlanController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $plans =  ActivePlan::orderBy('id', 'desc')->where('is_active', true)->paginate($request->input('perPage'));
        if($request->input('search')){
            $plans =  ActivePlan::orderBy('id', 'desc')
                ->where('is_active', true)
                ->whereHas('member', function ($query) use ($request) {
                     $query->where('name', 'like', "%".$request->input('search')."%");
                })
                ->paginate($request->input('perPage'));
        }
        return ActivePlanResource::collection($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivePlanRequest $request)
    {
        //
        $request = $request->validated();

        try {
            $member = Member::find($request['memberId']);
            $plan =  MembershipPlan::find($request['planId']);

            $existingUnpaidPlans = ActivePlan::where('member_id', $request['memberId'])
                ->where('is_active', true)
                ->get()
                ->filter(function ($plan) {
                    return $plan->total_paid < $plan->total_amount;
                });

            if($existingUnpaidPlans->count() > 0){
                return $this->error([], 'You have an unpaid plan. Please clear it first', 501 );
            }

            $existingOverpaidPlans = ActivePlan::where('member_id', $request['memberId'])
                ->where('is_active', true)
                ->get()
                ->filter(function ($plan) {
                    return $plan->total_paid > $plan->total_amount;
                })
                ->map(function ($plan) {
                    $excess = $plan->total_paid - $plan->total_amount;
                    $plan->total_paid = $plan->total_amount;
                    $plan->save();

                    return $excess;
                });

            $totalExcess = $existingOverpaidPlans->sum();

            //return an error if there is an existing running plan
            if($member->activePlans()->where('is_active', true)->count() > 0){
                return $this->error([], 'You have an active plan. Please clear it first', 501 );
            }

            ActivePlan::where('member_id', $request['memberId'])
                ->where('is_active', true)
                ->update(['is_active' => false]);

            if($request['deposit'] == 0 && $totalExcess == 0){
                return $this->error([], 'You have no deposit to renew', 501 );
            }

            $totalDeposit = $request['deposit'] + $totalExcess;

            Transaction::create([
                'amount' => $request['deposit'],
                'narration' => 'Deposit for renewal for member '.$member->name,
                'type' => 'credit',
                'category' => TransactionCategory::MEMBERSHIP_DEPOSIT,
                'transaction_date' => date('Y-m-d', strtotime($request['paymentDate'])),
                'payment_method_id' => $request['paymentMethodId'],
            ]);

            $endDate = "";
            $startDate = new DateTime($request['startDate']);

            switch ($plan->duration) {
                case 'daily':
                    $endDate = $startDate->modify("+1 day")->format('Y-m-d');
                    break;
                case 'weekly':
                    $endDate = $startDate->modify("+1 week")->format('Y-m-d');
                    break;
                case 'monthly':
                    $endDate = $startDate->modify("+1 month")->format('Y-m-d');
                    break;
                case 'yearly':
                    $endDate = $startDate->modify("+1 year")->format('Y-m-d');
                    break;
                case 'biannually':
                    $endDate = $startDate->modify("+6 months")->format('Y-m-d');
                    break;
            }

            $member->update([
                'membership_plan_id' => $request['planId'],
            ]);

            $newPlan = $member->activePlans()->create([
                'membership_plan_id' => $request['planId'],
                'total_paid' => $totalDeposit,
                'total_amount' => $plan->price,
                'start_date' => date('Y-m-d', strtotime($request['startDate'])),
                'end_date' => date('Y-m-d', strtotime($endDate)),
            ]);

            return $this->success(new ActivePlanResource($newPlan), 'Plan renewed successfully');


        } catch (\Throwable $th) {
            return $this->error($th, $th->getMessage(), 501 );
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(ActivePlan $activePlan)
    {
        //
        return new ActivePlanResource($activePlan);
    }

    /**
     * Update the specified resource in storage. only use if you intend to use a new date for a start date, kinda like a renew but in place
     */
    public function update(UpdateActivePlanRequest $request, ActivePlan $activePlan)
    {
        $request = $request->validated();

        try {
            $member = $activePlan->member();
            $plan = MembershipPlan::find($request['planId']); // Assuming planId is passed


            //Validation: Check for existing unpaid plans for the member
            $existingUnpaidPlans = ActivePlan::where('member_id', $member['id'])
                ->where('is_active', true)
                ->where('id', '!=', $activePlan->id) //Exclude the plan being updated
                ->filter(function ($plan) {
                    return $plan->total_paid < $plan->total_amount;
                });

            if ($existingUnpaidPlans->count() > 0) {
                return $this->error([], 'You have an unpaid plan. Please clear it first', 501);
            }

            //Handle excess payments from previous plans (similar to store method)
            $existingOverpaidPlans = ActivePlan::where('member_id', $member['id'])
                ->where('is_active', true)
                ->filter(function ($plan) {
                    return $plan->total_paid > $plan->total_amount;
                })
                ->map(function ($plan) {
                    $excess = $plan->total_paid - $plan->total_amount;
                    $plan->total_paid = $plan->total_amount;
                    $plan->save();
                    return $excess;
                });

            $totalExcess = $existingOverpaidPlans->sum();

            //Update existing active plan
            $startDate = new DateTime($request['startDate']);

            $endDate = '';
            switch ($plan->duration) {
                case 'daily':
                    $endDate = $startDate->modify('+1 day')->format('Y-m-d');
                    break;
                case 'weekly':
                    $endDate = $startDate->modify('+1 week')->format('Y-m-d');
                    break;
                case 'monthly':
                    $endDate = $startDate->modify('+1 month')->format('Y-m-d');
                    break;
                case 'yearly':
                    $endDate = $startDate->modify('+1 year')->format('Y-m-d');
                    break;
                case 'biannually':
                    $endDate = $startDate->modify('+6 months')->format('Y-m-d');
                    break;
            }

            $activePlan->update([
                'membership_plan_id' => $request['planId'],
                'total_paid' => $request['deposit'] + $totalExcess, //Add excess payment
                'total_amount' => $plan->price,
                'start_date' => date('Y-m-d', $startDate),
                'end_date' => $endDate,
            ]);

            //Consider adding Transaction update or creation here if payment details are updated.
            if($request['deposit'] > 0){
                Transaction::create([
                    'amount' => $request['deposit'],
                    'narration' => 'Deposit for update to new plan for member '.$member->name,
                    'type' => 'credit',
                    'category' => TransactionCategory::MEMBERSHIP_DEPOSIT,
                    'transaction_date' => date('Y-m-d', $request['paymentDate']),
                    'payment_method_id' => $request['paymentMethodId'],
                ]);
            }


            return $this->success(new ActivePlanResource($activePlan), 'Plan updated successfully');

        } catch (\Throwable $th) {
            return $this->error([], 'Something went wrong', 501);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivePlan $activePlan)
    {
        //
        $activePlan->update([
            'is_active' => false,
        ]);
        return $this->success(null, 'Plan cancelled successfully');
    }

    public function addPaymentOnPlan(PaymentRequest $request, ActivePlan $activePlan)
    {
        $request = $request->validated();
        $totalDeposit =  $request['amount'] + $activePlan['total_paid'];
        $activePlan->update([
            'total_paid' => $totalDeposit,
        ]);

        Transaction::create([
            'amount' => $request['amount'],
            'narration' => 'Deposit on existing plan for member '.$activePlan['member']['name'],
            'type' => 'credit',
            'category' => TransactionCategory::MEMBERSHIP_DEPOSIT,
            'transaction_date' => date('Y-m-d', strtotime($request['paymentDate'])),
            'payment_method_id' => $request['paymentMethodId'],
        ]);

        return $this->success(new ActivePlanResource($activePlan), 'Payment added successfully');

    }
}
