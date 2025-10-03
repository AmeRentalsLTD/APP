<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RentalAgreementStoreRequest;
use App\Http\Requests\RentalAgreementUpdateRequest;
use App\Http\Resources\RentalAgreementResource;
use App\Models\RentalAgreement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class RentalAgreementController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $agreements = RentalAgreement::query()
            ->with(['vehicle', 'customer'])
            ->latest('start_date')
            ->paginate(perPage: request('per_page', 15));

        return RentalAgreementResource::collection($agreements);
    }

    public function store(RentalAgreementStoreRequest $request): JsonResponse
    {
        $agreement = RentalAgreement::create($request->validated());

        return RentalAgreementResource::make($agreement->load(['vehicle', 'customer']))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RentalAgreement $rentalAgreement): RentalAgreementResource
    {
        return RentalAgreementResource::make($rentalAgreement->load(['vehicle', 'customer']));
    }

    public function update(RentalAgreementUpdateRequest $request, RentalAgreement $rentalAgreement): RentalAgreementResource
    {
        $rentalAgreement->fill($request->validated());
        $rentalAgreement->save();

        return RentalAgreementResource::make($rentalAgreement->load(['vehicle', 'customer']));
    }

    public function destroy(RentalAgreement $rentalAgreement): JsonResponse
    {
        $rentalAgreement->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
