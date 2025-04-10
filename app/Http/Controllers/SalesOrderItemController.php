<?php

namespace App\Http\Controllers;

use App\Models\SalesOrderItem;
use App\Http\Requests\StoreSalesOrderItemRequest;
use App\Http\Requests\UpdateSalesOrderItemRequest;

class SalesOrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreSalesOrderItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrderItem $salesOrderItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesOrderItem $salesOrderItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesOrderItemRequest $request, SalesOrderItem $salesOrderItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrderItem $salesOrderItem)
    {
        //
    }
}
