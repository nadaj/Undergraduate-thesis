<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Hash;
use App\User;
use App\Department;
use App\Title;
use App\Voting;
use App\Answer;
use App\Ticket;
use Carbon\Carbon;
use DB;
use SendGrid;
use Illuminate\Support\Str;
use Session;

class InitiatorController extends Controller
{
    public function getInitiatorHome()
	{
		$votings = Voting::all();
		return view('initiator.home', compact('votings'));
	}

	public function getChangePassword()
	{
		return view('initiator.changepassword');
	}

	public function postChangePassword(Request $request)
	{
		$this->validate($request, [
			'old_password' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

		$user = Auth::user();

		if (!Hash::check($request['old_password'], $user->password))
		{
			return redirect()->back()->with(['fail' => 'Ne poklapa se stara lozinka sa lozinkom vezanom za nalog.']);
		}

		User::where('email', '=', $user->email)->update([
			'password' => bcrypt($request['password'])
		]);

		return redirect()->back()->with(['success' => 'Uspešno je promenjena lozinka.']);
	}

	public function getVotings()
	{
		$my_votings = Voting::where('initiator_id', '=', Auth::user()->id)->get();
		return view('initiator.votings', compact('my_votings'));
	}

	public function getCreateVoting()
	{
		$katedre = Department::all();
		return view('initiator.createvoting', compact('katedre'));
	}

	public function createVoting(Request $request)
	{
		$datenow = Carbon::now();
		Session::forget('vote_success');
		$this->validate($request, [
			'naslov' => 'required|max:100|unique:votings,name',
            'opis' => 'required|max:65535',
            'vreme1' => 'required:date|after:' . $datenow,
            'vreme2' => 'required:date|after:vreme1',
            'odgovori' => 'required|min:2',
            'glasaci' => 'required|min:1',
	    ]);

		if ($request['pokreni'] === "")
		{
			// create votings entry
			DB::table('votings')->insert([
	        	'name' => $request['naslov'], 
			    'description' => $request['opis'], 
			    'from' => date( 'Y-m-d H:i:s', strtotime($request['vreme1'])), 
			    'to' => date( 'Y-m-d H:i:s', strtotime($request['vreme2'])), 
			    'initiator_id' => Auth::user()->id
			]);

			$last_votings_id = DB::table('votings')->max('id');
			
			// add answers entries
			foreach ($request['odgovori'] as $odgovor)
			{
				DB::table('answers')->insert([
					'votings_id' => $last_votings_id,
	        		'answer' => $odgovor
				]);
			}

			// send to all voters an email with the ticket 
			foreach ($request['glasaci'] as $glasac)
			{
				// creating ticket encrypted with voter's hashed password
				$nonce = Str::random(32);
				$password = User::where('email', '=', $glasac)->get()->first()->password;
				$hexstr = unpack('H*', $password);
				$key = substr(array_shift($hexstr), 0, 32);
				
				// create a random IV to use with CBC encoding
				$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
				$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

				$hashed_nonce = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $nonce, MCRYPT_MODE_CBC, $iv);

				$hashed_nonce = $iv . $hashed_nonce;
				$hashed_nonce = base64_encode($hashed_nonce);

				DB::table('tickets')->insert([
					'votings_id' => $last_votings_id,
	        		'nonce' => $nonce
				]);

				$sendgrid = new SendGrid('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');
	        	$email = new SendGrid\Email();
	       
		        $m = "<p>Glasanje:<br/><br/>
		        	Naziv: " . $request['naslov'].
		        	"<br/>Opis: " . $request['opis'] . 
		        	"<br/>Vreme početka: " . date('d-m-Y H:i:s', strtotime($request['vreme1'])).
		        	"<br/>Vreme završetka: " . date('d-m-Y H:i:s', strtotime($request['vreme2'])).
 		        	"<br/><br/><b>Tiket koji je potrebno uneti pri glasanju:<br/>".
 		        	$hashed_nonce . "</b></p>";
	   
	        $email
	            ->addTo($glasac)
	            ->setFrom('votingsystemetf@gmail.com')
	            ->setSubject('e-Glasanje: Dobili ste pozivnicu za glasanje')
	            ->setHtml($m)
	        ;

	        try {
	            $sendgrid->send($email);

	        } catch(\SendGrid\Exception $e) {
	            echo $e->getCode();
	            foreach($e->getErrors() as $er) {
	                echo $er;
	            }
	        }	
			}
			
			Session::flash('vote_success', 'Uspešno je kreirano glasanje.');
			Session::flash('count', '1');

			$votings = Voting::all();
			return view('initiator.home', compact('votings'));
		}
		else
		{
			$voting = new Voting([
				'name' => $request['naslov'], 
			    'description' => $request['opis'], 
			    'from' => date( 'Y-m-d H:i:s', strtotime($request['vreme1'])), 
			    'to' => date( 'Y-m-d H:i:s', strtotime($request['vreme2'])), 
			    'initiator_id' => Auth::user()->id
			]);

			$i = 0;

			foreach ($request['odgovori'] as $odgovor)
			{
				$answers[$i++] = new Answer([
	        		'answer' => $odgovor
				]);
			}

			$voters = $request['glasaci'];

			$request->session()->put('naslov', $request['naslov']);
			$request->session()->put('opis', $request['opis']);
			$request->session()->put('vreme1', $request['vreme1']);
			$request->session()->put('vreme2', $request['vreme2']);
			$request->session()->put('odgovori', $request['odgovori']);
			$request->session()->put('glasaci', $request['glasaci']);

			return view('initiator.reviewvoting', compact('answers', 'voting', 'voters'));
		}
		
	}

	public function getZvanja(Request $request)
	{
		$zvanja = User::join('titles', 'users.title_id', '=', 'titles.id')
						->select('titles.id', 'titles.name')
						->where('users.department_id', '=', $request['katedra'])
						->whereRaw('users.active = 1 and users.confirmed = 1')
						->distinct()
						->get();

		return $zvanja;
	}

	public function getKorisnici(Request $request)
	{
		$korisnici = User::where('department_id', '=', $request['katedra'])
						->where('title_id', '=', $request['zvanje'])
						->whereRaw('active = 1 and confirmed = 1')
						->get();

		return $korisnici;
	}

	public function createReviewedVoting(Request $request)
	{
		Session::forget('vote_success');
		DB::table('votings')->insert([
			'name' => $request->session()->get('naslov'), 
		    'description' => $request->session()->get('opis'), 
		    'from' => date( 'Y-m-d H:i:s', strtotime($request->session()->get('vreme1'))), 
		    'to' => date( 'Y-m-d H:i:s', strtotime($request->session()->get('vreme2'))), 
		    'initiator_id' => Auth::user()->id
		]);

		$last_votings_id = DB::table('votings')->max('id');

		// add answers entries
		foreach ($request->session()->get('odgovori') as $odgovor)
		{
			DB::table('answers')->insert([
				'votings_id' => $last_votings_id,
        		'answer' => $odgovor
			]);
		}

		// send to all voters an email with the ticket 
		foreach ($request->session()->get('glasaci') as $glasac)
		{
			// creating ticket encrypted with voter's hashed password
			$nonce = Str::random(32);
			$password = User::where('email', '=', $glasac)->get()->first()->password;
			$hexstr = unpack('H*', $password);
			$key = substr(array_shift($hexstr), 0, 32);
			
			// create a random IV to use with CBC encoding
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

			$hashed_nonce = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $nonce, MCRYPT_MODE_CBC, $iv);

			$hashed_nonce = $iv . $hashed_nonce;
			$hashed_nonce = base64_encode($hashed_nonce);

			DB::table('tickets')->insert([
				'votings_id' => $last_votings_id,
        		'nonce' => $nonce
			]);

			$sendgrid = new SendGrid('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');
        	$email = new SendGrid\Email();
       
	        $m = "<p>Glasanje:<br/><br/>
	        	Naziv: " . $request->session()->get('naslov').
	        	"<br/>Opis: " . $request->session()->get('opis') . 
	        	"<br/>Vreme početka: " . date('d-m-Y H:i:s', strtotime($request->session()->get('vreme1'))).
	        	"<br/>Vreme završetka: " . date('d-m-Y H:i:s', strtotime($request->session()->get('vreme2'))).
		        	"<br/><br/><b>Tiket koji je potrebno uneti pri glasanju:<br/>".
		        	$hashed_nonce . "</b></p>";
   
        $email
            ->addTo($glasac)
            ->setFrom('votingsystemetf@gmail.com')
            ->setSubject('e-Glasanje: Dobili ste pozivnicu za glasanje')
            ->setHtml($m)
        ;

        try {
            $sendgrid->send($email);

        } catch(\SendGrid\Exception $e) {
            echo $e->getCode();
            foreach($e->getErrors() as $er) {
                echo $er;
            }
        }	
		}

		$request->session()->forget('naslov');
		$request->session()->forget('opis');
		$request->session()->forget('vreme1');
		$request->session()->forget('vreme2');
		$request->session()->forget('odgovori');
		$request->session()->forget('glasaci');

		Session::flash('vote_success', 'Uspešno je kreirano glasanje.');
		Session::flash('count', '1');

		$votings = Voting::all();
		return view('initiator.home', compact('votings'));
	}

	public function getVotingInfo($votings_id)
	{
		$voting = Voting::where('id', '=', $votings_id)->firstOrFail();
		return view('initiator.voting', compact('voting'));
	}

	public function getAccessVote(Request $request)
	{
		if ($request->ajax())
		{
			$this->validate($request, [
				'ticket' => 'required'
        	]);
			
			try
			{	
				$ticket = base64_decode($request['ticket']);

				$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
				$iv_dec = substr($ticket, 0, $iv_size);
				$ticket = substr($ticket, $iv_size);

				$hexstr = unpack('H*', Auth::user()->password);
				$key = substr(array_shift($hexstr), 0, 32);
				
				$ticket = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $ticket, MCRYPT_MODE_CBC, $iv_dec);
				
				$t = Ticket::where('votings_id', '=', $request['voting_id'])
								->where('nonce', '=', $ticket)
								->get();

			}catch(\Exception $e) {
				return response()->json(["name"=> "Nije validan tiket!"], 422);
			}

			if ($t->isEmpty())
        		return response()->json(["name"=> "Nije validan tiket!"], 422);

        	// if passed, voting is allowed
        	return $t->first()->id;
		}
	}

	public function postVote(Request $request)
	{
		Session::forget('vote_success');
		$this->validate($request, [
			'optionsRadios' => 'required'
        ]);

		//	save answer
		Ticket::where('id', '=', $request['ticket'])->update([
			'answers_id' => $request['optionsRadios']
		]);

		Session::flash('vote_success', 'Uspešno ste glasali!');
		Session::flash('count', '1');
		return redirect()->route('initiator.home');
	}

	public function getVote($ticket_id)
	{
		//Session::forget('vote_success');
		$votings_id = Ticket::where('id', '=', $ticket_id)->get();
		if ($votings_id->isEmpty())
			return redirect('error')->with('fail', 'Nije validan zahtev!');

		$voting = Voting::where('id','=', $votings_id->first()->votings_id)->get()->first();
		$answers = Answer::where('votings_id', '=', $votings_id->first()->votings_id)->get();
		
		return view('initiator.vote', compact('ticket_id', 'answers', 'voting'));
	}
}
