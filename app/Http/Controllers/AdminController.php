<?php

namespace diplomski_rad\Http\Controllers;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAdminHome()
	{
		return view('admin.home');
	}
}
