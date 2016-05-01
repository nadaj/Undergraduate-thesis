<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Events\RegisterEvent;
use Event;

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

	public function postRegister(Request $request)
	{
		// Validation of the request
		$this->validate($request, [
			'email' => 'required|email|unique:users,email|unique:temp_users,email|max:255',
			'fname' => 'required|max:50|alpha',
			'lname' => 'required|max:50|alpha',
			'title' => 'required',
			'department' => 'required'
		]);

		// Insert into temp_users table
        DB::table('temp_users')->insert([
        	'firstname' => $request['fname'], 
		    'lastname' => $request['lname'], 
		    'email' => $request['email'], 
		    'department_id' => intval($request['department']), 
		    'title_id' => intval($request['title']), 
		]);

        Event::fire(new RegisterEvent($request['email'], $request['fname']." ".$request['lname']));

		return redirect()->back()->with(['success' => 'Uspešno su uneti podaci za registraciju! 
		Primićete poruku na Vašoj e-mail adresi.']);
	}	
}