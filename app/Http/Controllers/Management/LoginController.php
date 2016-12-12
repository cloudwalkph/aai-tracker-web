<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;

class LoginController extends Controller {
    public function index()
    {
        config(['app.name' => 'Insite Management Login']);

        return view('insite.login');
    }
}