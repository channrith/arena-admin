<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarManagementController extends Controller
{
    public function index()
    {
        return view('cars.index');
    }
}
