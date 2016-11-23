<?php
namespace App\Http\Controllers\Insite;

use App\Http\Controllers\Controller;

class LoginController extends Controller {
    public function index()
    {
        config('app.name', 'Insite Dashboard');

        return view('insite.home');
    }
}