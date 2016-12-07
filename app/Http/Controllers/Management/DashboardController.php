<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;

class DashboardController extends Controller {
    public function index()
    {
        config(['app.name' => 'Insite Management']);

        return view('home');
    }
}