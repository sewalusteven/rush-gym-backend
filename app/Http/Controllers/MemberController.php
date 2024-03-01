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
        return MemberResource::collection(Member::paginate(10));
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
            'email' => $request->input('name'),
            'phone_number' => $request->input('name'),
            'membership_plan_id' => $request->input('name'),
            'start_date' => $request->input('name'),
            'end_date' => $request->input('name'),
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }
}
