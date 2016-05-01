<?php

namespace diplomski_rad\Http\Controllers;
use Illuminate\Http\Request;
use Auth;

class UserController extends Controller {

	public function getLogin()
	{
		return view('login');
	}

	public function getLogout()
	{
		Auth::logout();
		return redirect()->route('login');
	}

	public function postLogin(Request $request)
	{
		// Validation of the request
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required'
		]);

		if (!Auth::attempt(['email' => $request['email'], 'password' => $request['password'], 'active' => 1]))
		{
			return redirect()->back()->with(['fail' => 'Uneti podaci nisu validni!']);
		}
		return redirect()->route('admin.home');
	}

	public function getRegister()
	{
		return view('register');
	}
}