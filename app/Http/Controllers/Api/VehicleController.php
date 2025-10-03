<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleStoreRequest;
use App\Http\Requests\VehicleUpdateRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $vehicles = Vehicle::query()
            ->orderBy('registration')
            ->paginate(perPage: request('per_page', 15));

        return VehicleResource::collection($vehicles);
    }

    public function store(VehicleStoreRequest $request): JsonResponse
    {
        $vehicle = Vehicle::create($request->validated());

        return VehicleResource::make($vehicle)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Vehicle $vehicle): VehicleResource
    {
        return VehicleResource::make($vehicle);
    }

    public function update(VehicleUpdateRequest $request, Vehicle $vehicle): VehicleResource
    {
        $vehicle->fill($request->validated());
        $vehicle->save();

        return VehicleResource::make($vehicle);
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
