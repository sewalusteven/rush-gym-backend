<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return ServiceResource::collection(Service::all());
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
    public function store(StoreServiceRequest $request)
    {
        //
        $service = Service::create([
            'service' => $request->input('service'),
            'description' => $request->input('description'),
            'price' => $request->input('price')
        ]);
        return new ServiceResource($service);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $service)
    {
        //
        $service = Service::findOrFail($service);
        return new ServiceResource($service);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, int $service)
    {
        //
        $service = Service::findOrFail($service);
        $service->update($request->validated());
        return new ServiceResource($service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $service)
    {
        //
        $service = Service::findOrFail($service);
        $service->delete();
        return response(['message' => 'service deleted successfully']);
    }
}
