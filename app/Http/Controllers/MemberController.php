<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Models\MembershipPlan;
use DateTime;
use Exception;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return MemberResource::collection(Member::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @throws Exception
     */
    public function store(StoreMemberRequest $request)
    {
        $plan = MembershipPlan::find($request->input('planId'));
        $start_date = new DateTime($request->input('start'));
        $end ="";

        switch ($plan['duration']){
            case 'daily':
                $start_date->modify("+1 day");
                $end = $start_date->format('Y-m-d');
                break;
            case 'weekly':
                $start_date->modify("+1 week");
                $end = $start_date->format('Y-m-d');
                break;
            case 'monthly':
                $start_date->modify("+1 month");
                $end = $start_date->format('Y-m-d');
                break;
            case 'yearly':
                $start_date = new DateTime('2024-02-14');
                $start_date->modify("+1 year");
                $end = $start_date->format('Y-m-d');
                break;
        }
        //
        $member = Member::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone'),
            'membership_plan_id' => $request->input('planId'),
            'start_date' => date('Y-m-d', strtotime($request->input('start'))),
            'end_date' => date('Y-m-d', strtotime($end)),
        ]);

        return new MemberResource($member);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $member)
    {
        return new MemberResource(Member::findOrFail($member));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, int $member)
    {
        //
        $member = Member::findOrFail($member);
        $member->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'membership_plan_id' => $request->input('membership_plan_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
        return new MemberResource($member);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $member)
    {
        //
        $toBeDeleted = Member::findOrFail($member);
        $toBeDeleted->delete();
        return response(['message' => 'member deleted successfully']);
    }
}
