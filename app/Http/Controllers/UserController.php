<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;

class UserController extends Controller {

	public function getLogin()
	{
		if (Auth::user() == null)
			return view('login');
		if (Auth::user()->role_id == 1)
			return redirect()->route('admin.home');
		else if (Auth::user()->role_id == 2)
			return redirect()->route('initiator.home');
		else
			return redirect()->route('voter.home');
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
			'email' => 'required|email',
			'password' => 'required'
		]);

		if (!Auth::attempt(['email' => $request['email'], 'password' => $request['password'], 'active' => 1, 'confirmed' => 1]))
		{
			return redirect()->back()->with(['fail' => 'Uneti podaci nisu validni!']);
		}

		if (Auth::user()->role_id == 1)
		{
			return redirect()->route('admin.home');
		}
		else if (Auth::user()->role_id == 2)
		{
			return redirect()->route('initiator.home');
		}
		else
		{
			return redirect()->route('voter.home');
		}
	}

	public function getRegister()
	{
		return view('register');
	}

	public function postRegister(Request $request)
	{
		// Validation of the request
		$this->validate($request, [
			'email' => 'required|email|unique:users,email|max:255',
			'fname' => 'required|max:50|alpha',
			'lname' => 'required|max:50|alpha',
			'title' => 'required',
			'department' => 'required'
		]);

        DB::table('users')->insert([
        	'firstname' => $request['fname'], 
		    'lastname' => $request['lname'], 
		    'email' => $request['email'], 
		    'department_id' => intval($request['department']), 
		    'title_id' => intval($request['title']), 
		    'role_id' => 3
		]);

		return redirect()->back()->with(['success' => 'Uspešno su uneti podaci za registraciju! 
		Primićete poruku na Vašoj e-mail adresi o daljoj registraciji.']);
	}	

	public function verifyRegister($confirmation_code)
	{
		if (! $confirmation_code)
			return redirect()->route('error')->with([
            'fail' => 'Nije validan zahtev za registraciju!'
        ]);

        $user = User::where('confirmation_code', '=', $confirmation_code)->get();

        if ($user->isEmpty())
	        return redirect()->route('error')->with([
	            'fail' => 'Nije validan zahtev za registraciju!'
	        ]);

	    User::where('confirmation_code', '=', $confirmation_code)->update([
            'confirmed' => true,
            'confirmation_code' => null
        ]);

	    return redirect()->route('login');
	}
}