<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembershipPlanRequest;
use App\Http\Requests\UpdateMembershipPlanRequest;
use App\Http\Resources\MembershipPlanResource;
use App\Models\MembershipPlan;

class MembershipPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  MembershipPlanResource::collection(MembershipPlan::all());
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
     */
    public function store(StoreMembershipPlanRequest $request)
    {
        $plan = MembershipPlan::create([
            'name' => $request->input('name'),
            'duration' => $request->input('duration'),
            'price' => $request->input('price')
        ]);

        return new MembershipPlanResource($plan);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $membershipPlan)
    {
        //
        $plan = MembershipPlan::findOrFail($membershipPlan);

        return new MembershipPlanResource($plan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MembershipPlan $membershipPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembershipPlanRequest $request, int $membershipPlan)
    {
        //
        $plan = MembershipPlan::findOrFail($membershipPlan);
        $plan->update([
            'name' => $request->input('name'),
            'duration' => $request->input('duration'),
            'price' => $request->input('price'),
        ]);
        return new MembershipPlanResource($plan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $membershipPlan)
    {
        //
        $plan = MembershipPlan::findOrFail($membershipPlan);
        $plan->delete();
        return response(['message' => 'plan deleted successfully']);
    }
}
