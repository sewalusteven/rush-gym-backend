<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Http\Traits\HttpResponses;
use App\Models\Member;
use App\Models\MembershipPlan;
use DateTime;
use Exception;

class MemberController extends Controller
{
    use HttpResponses;
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

        $member = Member::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone')
        ]);

        return $this->success(new MemberResource($member), 'Member Added Successfully', 200);
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
    public function counts()
    {
        //
        $daily =  Member::getDailyMembers();
        $monthly = Member::getMonthlyMembers();
        $total = Member::count();
        return $this->success(['daily' => $daily, 'monthly' => $monthly, 'total' => $total]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        //
        $request =  $request->validated();
        $member->update([
            'email' => $request['email'],
            'phone_number' => $request['phone'],
        ]);
        return $this->success(new MemberResource($member), 'Member Updated Successfully', 200);
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
