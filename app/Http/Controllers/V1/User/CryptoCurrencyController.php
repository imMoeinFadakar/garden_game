<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Models\cryptocurrency;
use Illuminate\Http\Request;

class CryptoCurrencyController extends Controller
{
    /**
     * get list of user crypto currency history
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function index(Request $request)
    {
        // $cryptocurrency = cryptocurrency::where("user_id",1) // add auth::id() later
         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
