<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;

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
     */
    public function store(StoreMemberRequest $request)
    {
        //
        $member = Member::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'membership_plan_id' => $request->input('membership_plan_id'),
            'start_date' => date('Y-m-d', strtotime($request->input('start_date'))),
            'end_date' => date('Y-m-d', strtotime($request->input('end_date'))),
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
