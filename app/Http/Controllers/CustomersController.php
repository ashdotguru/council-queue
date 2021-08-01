<?php

namespace App\Http\Controllers;

use App\Models\ {
    Citizen,
    Customer,
    Organisation,
    Service
};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomersController extends Controller
{
    /**
     * Show the waiting queue.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $customers = Customer::with('service')
                ->orderBy('queued_at')
                ->get();
            $services = Service::orderBy('name')->get();

            return response()->json([
                'customers' => $customers,
                'services' => $services,
            ]);
        }

        return view('queue');
    }

    /**
     * Store a new customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'service' => 'required|exists:services,id',
            'type' => [
                'required',
                Rule::in(['anonymous', 'citizen', 'organisation']),
            ],
            'title' => [
                'nullable',
                'required_if:type,citizen',
                Rule::in(['Dr.', 'Miss', 'Mr.', 'Mrs.', 'Ms.', 'Prof.']),
            ],
            'first_name' => 'nullable|required_if:type,citizen|string|min:2|max:50',
            'last_name' => 'nullable|required_if:type,citizen|string|min:2|max:50',
            'name' => 'nullable|required_if:type,organisation|string|max:100',
        ]);

        $models = collect([
            'citizen' => Citizen::class,
            'organisation' => Organisation::class,
        ]);

        if ($request->type === 'anonymous') {
            $customer = Customer::create([
                'service_id' => $request->service,
                'queued_at' => now(),
            ]);
        } else {
            $model = $models->get($request->type)::create(
                $request->only(['title', 'first_name', 'last_name', 'name'])
            );

            $customer = $model->customer()->create([
                'service_id' => $request->service,
                'queued_at' => $model->created_at,
            ]);
        }

        $customer->load('service');

        return response()->json([
            'customer' => $customer,
            'message' => 'The customer has been added to the queue.',
        ], 201);
    }

    /**
     * Delete a customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'message' => 'The customer has been removed from the queue.',
        ]);
    }
}
