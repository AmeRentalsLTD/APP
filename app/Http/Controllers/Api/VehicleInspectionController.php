<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleInspectionStoreRequest;
use App\Http\Requests\VehicleInspectionUpdateRequest;
use App\Http\Resources\VehicleInspectionResource;
use App\Models\VehicleInspection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class VehicleInspectionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $inspections = VehicleInspection::query()
            ->latest('inspected_at')
            ->with('vehicle:id,registration')
            ->paginate(perPage: request('per_page', 15));

        return VehicleInspectionResource::collection($inspections);
    }

    public function store(VehicleInspectionStoreRequest $request): JsonResponse
    {
        $inspection = VehicleInspection::create($request->validated());

        $inspection->load('vehicle:id,registration');

        return VehicleInspectionResource::make($inspection)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(VehicleInspection $vehicleInspection): VehicleInspectionResource
    {
        $vehicleInspection->loadMissing('vehicle:id,registration');

        return VehicleInspectionResource::make($vehicleInspection);
    }

    public function update(
        VehicleInspectionUpdateRequest $request,
        VehicleInspection $vehicleInspection
    ): VehicleInspectionResource {
        $vehicleInspection->fill($request->validated());
        $vehicleInspection->save();

        $vehicleInspection->loadMissing('vehicle:id,registration');

        return VehicleInspectionResource::make($vehicleInspection);
    }

    public function destroy(VehicleInspection $vehicleInspection): JsonResponse
    {
        $vehicleInspection->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
