<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $customers = Customer::query()
            ->orderBy('company_name')
            ->orderBy('last_name')
            ->paginate(perPage: request('per_page', 15));

        return CustomerResource::collection($customers);
    }

    public function store(CustomerStoreRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());

        return CustomerResource::make($customer)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Customer $customer): CustomerResource
    {
        return CustomerResource::make($customer);
    }

    public function update(CustomerUpdateRequest $request, Customer $customer): CustomerResource
    {
        $customer->fill($request->validated());
        $customer->save();

        return CustomerResource::make($customer);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
