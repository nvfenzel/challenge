<?php

namespace App\Http\Controllers;

use App\Models\AdminController;
use Illuminate\Http\Request;

class AdminControllerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'admin';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminController  $adminController
     * @return \Illuminate\Http\Response
     */
    public function show(AdminController $adminController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdminController  $adminController
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminController $adminController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminController  $adminController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminController $adminController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminController  $adminController
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminController $adminController)
    {
        //
    }
}
