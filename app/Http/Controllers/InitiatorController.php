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
use Moment\Moment;

class InitiatorController extends Controller
{
    public function getInitiatorHome()
	{
		$date_now = Carbon::now();
		$votings = Voting::where('from', '<=', $date_now)
						->where('to', '>=', $date_now)
						->simplePaginate(5, ['*'], 'page_current');		
		$temp_current = Voting::where('from', '<=', $date_now)
						->where('to', '>=', $date_now)->get();

		$date_now = str_replace(' ', 'T', $date_now);
		// setting progresses for current votings
		for($i = 0; $i < count($temp_current); $i++)
		{
			$start = new \Moment\Moment($temp_current[$i]->from);
			$duration = $start->from($temp_current[$i]->to);
			$duration_now = $start->from($date_now);
			$duration_days = $duration->getDays();
			$duration_now_days = $duration_now->getDays();

			if ($duration_days == 0)
			{
				$duration_now_days = $duration_now->getMinutes();
				$duration_days = $duration->getMinutes();
			}
			$progresses[$i] = ($duration_now_days / $duration_days) * 100;
		}

		return view('initiator.home', compact('votings', 'progresses'));
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
		$date_now = Carbon::now();
		$my_votings = Voting::where('initiator_id', '=', Auth::user()->id)
							->where('from', '<=', $date_now)
							->where('to', '>=', $date_now)
							->simplePaginate(5, ['*'], 'page_current');	
		$my_votings_past = Voting::where('initiator_id', '=', Auth::user()->id)
							->where('to', '<', $date_now)
							->simplePaginate(5, ['*'], 'page_past');		
		$temp_current = Voting::where('initiator_id', '=', Auth::user()->id)
							->where('from', '<=', $date_now)
							->where('to', '>=', $date_now)
							->get();
		$temp_past = Voting::where('initiator_id', '=', Auth::user()->id)
							->where('to', '<', $date_now)
							->get();
		$date_now = str_replace(' ', 'T', $date_now);
		// setting progresses and num of people that voted for current votings
		for ($i = 0; $i < count($temp_current); $i++)
		{
			$start = new \Moment\Moment($temp_current[$i]->from);
			$duration = $start->from($temp_current[$i]->to);
			$duration_now = $start->from($date_now);
			$duration_days = $duration->getDays();
			$duration_now_days = $duration_now->getDays();
			if ($duration_days == 0)
			{
				$duration_now_days = $duration_now->getMinutes();
				$duration_days = $duration->getMinutes();
			}
			$progresses[$i] = ($duration_now_days / $duration_days) * 100;

			$num_to_vote = Ticket::where('votings_id', '=', $temp_current[$i]->id)->count();

			$num_voted = Ticket::join('answers_tickets', 'tickets.id', '=', 'answers_tickets.tickets_id')
						->select('answers_tickets.tickets_id')
						->where('tickets.votings_id', '=', $temp_current[$i]->id)
						->distinct()
						->get()
						->count();

			$proc[$i] = ($num_voted * 100)/$num_to_vote;
		}

		for ($i = 0; $i < count($temp_past); $i++)
		{
			$answers = Answer::where('votings_id', '=', $temp_past[$i]->id)->get();
			$past_answers[$i] = '';
			//$num_to_vote = Ticket::where('votings_id', '=', $temp_past[$i]->id)->count();
			foreach ($answers as $answer) {
				$past_answers[$i] = $past_answers[$i] . $answer->answer . ": ";

				$num_voted = Ticket::join('answers_tickets', 'tickets.id', '=', 'answers_tickets.tickets_id')
						->where('tickets.votings_id', '=', $temp_past[$i]->id)
						->where('answers_tickets.answers_id', '=', $answer->id)
						->distinct()
						->count();

				$past_answers[$i] = $past_answers[$i] . $num_voted . "\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";							
			}
			$past_answers[$i] = nl2br($past_answers[$i]);
		}

		return view('initiator.votings', compact('my_votings', 'progresses', 'my_votings_past'
			, 'proc', 'past_answers'));
	}

	public function getCreateVoting()
	{
		$katedre = Department::all();
		$zvanja = Title::all();
		$korisnici = User::whereRaw('active = 1 and confirmed = 1')
						 ->where('title_id', '!=', null)
						 ->get();
		return view('initiator.createvoting', compact('katedre', 'zvanja', 'korisnici'));
	}

	public function createVoting(Request $request)
	{
		$datenow = Carbon::now();
		
		Session::forget('vote_success');
		$rules = [
			'naslov' => 'required|max:100|unique:votings,name',
            'opis' => 'required|max:65535',
            'vreme1' => 'required:date|after:' . $datenow,
            'vreme2' => 'required:date|after:vreme1',
            'glasaci' => 'required|min:1',
            'vrednost' => 'required|integer|between:0,' . count($request['glasaci']),
            'criteriumRadios' => 'required'
	    ];

	    if (!$request['relacija'] || $request['relacija'] === "0")
	    {
            $rules['relacija'] = 'required|different:0';
	    }

	    $i = 0;
	    $odgovori = array();
	    if ($request['odg'])
	    {
		    foreach ($request['odg'] as $odg) {
	    		if ($odg !== "" && !in_array(strtolower($odg), array_map('strtolower', $odgovori)))
	        	{
	        		$odgovori[$i] = $odg;
		        	$i++;
	        	}
		    }
		}

	    if ($i < 2)
	    {
	    	$rules['odgovori'] = 'required|min:2';
	    }
	   
	    if ($request['criteriumRadios'] && $request['criteriumRadios'] === "2")
	    {
	    	if ($request['opcija'] === "0")
	    	{
	    		$rules['opcija'] = 'required|different:0';
	    	}
	    	else if (!in_array($request['opcija'], $request['odg']))
			{
				$rules['opcija_odg'] = 'required';
			}
	    }
	    else
	    {
	    	$rules['opcija'] = 'required';
	    }

	    if ($request['vise_odg'])
	    {
	    	if ($request['minimum'] < 1 || $request['minimum'] > count($request['odg']))
	    	{
	    		$rules['minimum'] = 'array';
	    	}
	    	if ($request['maximum'] >  count($request['odg']))
	    	{
	    		$rules['maximum'] = 'array';
	    	}
	    	if ($request['minimum'] > $request['maximum'])
	    	{
	    		$rules['min_max'] = 'required';
	    	}
	    }

		$this->validate($request, $rules);

		if ($request['pokreni'] === "")
		{
			// create votings entry
			DB::table('votings')->insert([
	        	'name' => $request['naslov'], 
			    'description' => $request['opis'], 
			    'from' => date( 'Y-m-d H:i:s', strtotime($request['vreme1'])), 
			    'to' => date( 'Y-m-d H:i:s', strtotime($request['vreme2'])), 
			    'multiple_answers' => array_key_exists('vise_odg', $request->all()),
			    'min' => $request['minimum'],
			    'max' => $request['maximum'],
			    'initiator_id' => Auth::user()->id
			]);

			$last_votings_id = DB::table('votings')->max('id');
			
			// add answers entries
			foreach ($request['odg'] as $odgovor)
			{
				DB::table('answers')->insert([
					'votings_id' => $last_votings_id,
	        		'answer' => $odgovor
				]);
			}

			if ($request['criteriumRadios'] === "2")
			{
				$answer_id = Answer::where('votings_id', '=' ,$last_votings_id)
								   ->where('answer', '=', $request['opcija'])
								   ->get()->first();
				DB::table('voting_success')->insert([
					'answer_id' => $answer_id->id,
					'relation' => $request['relacija'],
					'value' => $request['vrednost'],
					'voting_id' => $last_votings_id
				]);
			}
			else
			{
				DB::table('voting_success')->insert([
					'relation' => $request['relacija'],
					'value' => $request['vrednost'],
					'voting_id' => $last_votings_id
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

			return $this->getInitiatorHome();
		}
		else
		{
			$voting = new Voting([
				'name' => $request['naslov'], 
			    'description' => $request['opis'], 
			    'from' => date( 'Y-m-d H:i:s', strtotime($request['vreme1'])), 
			    'to' => date( 'Y-m-d H:i:s', strtotime($request['vreme2'])), 
			    'multiple_answers' => array_key_exists('vise_odg', $request->all()),
			    'min' => $request['minimum'],
			    'max' => $request['maximum'],
			    'initiator_id' => Auth::user()->id
			]);
			$i = 0;

			foreach ($request['odg'] as $odgovor)
			{
				$answers[$i++] = new Answer([
	        		'answer' => $odgovor
				]);
			}

			$voters = $request['glasaci'];
			$more_ans = array_key_exists('vise_odg', $request->all());
			$request->session()->put('naslov', $request['naslov']);
			$request->session()->put('opis', $request['opis']);
			$request->session()->put('vreme1', $request['vreme1']);
			$request->session()->put('vreme2', $request['vreme2']);
			$request->session()->put('odg', $request['odg']);
			$request->session()->put('glasaci', $request['glasaci']);
			$request->session()->put('vise_odg', array_key_exists('vise_odg', $request->all()));
			$request->session()->put('relacija', $request['relacija']);
			$request->session()->put('vrednost', $request['vrednost']);
			$request->session()->put('criteriumRadios', $request['criteriumRadios']);
			$request->session()->put('opcija', $request['opcija']);
			$request->session()->put('minimum', $request['minimum']);
			$request->session()->put('maximum', $request['maximum']);

			$relacija = $request['relacija'];
			$vrednost = $request['vrednost'];
			$opcija = $request['opcija'];
			$criteriumRadios = $request['criteriumRadios'];

			return view('initiator.reviewvoting', compact('answers', 'voting', 'voters',
			 'more_ans', 'relacija', 'vrednost', 'opcija', 'criteriumRadios'));
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

	public function getKorisniciPoZK(Request $request)
	{
		$korisnici = User::where('department_id', '=', $request['katedra'])
						->where('title_id', '=', $request['zvanje'])
						->whereRaw('active = 1 and confirmed = 1')
						->get();

		return $korisnici;
	}

	public function getKorisniciPoZ(Request $request)
	{
		$korisnici = User::where('title_id', '=', $request['zvanje'])
						->whereRaw('active = 1 and confirmed = 1')
						->get();

		return $korisnici;
	}

	public function getKorisniciPoK(Request $request)
	{
		$korisnici = User::where('department_id', '=', $request['katedra'])
						->whereRaw('active = 1 and confirmed = 1')
						->get();

		return $korisnici;
	}

	public function createReviewedVoting(Request $request)
	{
		Session::forget('vote_success');
		if ($request['pokreni'] === "")
		{
			DB::table('votings')->insert([
				'name' => $request->session()->get('naslov'), 
			    'description' => $request->session()->get('opis'), 
			    'from' => date( 'Y-m-d H:i:s', strtotime($request->session()->get('vreme1'))), 
			    'to' => date( 'Y-m-d H:i:s', strtotime($request->session()->get('vreme2'))), 
			    'multiple_answers' => $request->session()->get('vise_odg'),
			    'min' => $request->session()->get('minimum'),
			    'max' => $request->session()->get('maximum'),
			    'initiator_id' => Auth::user()->id
			]);

			$last_votings_id = DB::table('votings')->max('id');

			// add answers entries
			foreach ($request->session()->get('odg') as $odgovor)
			{
				DB::table('answers')->insert([
					'votings_id' => $last_votings_id,
	        		'answer' => $odgovor
				]);
			}

			if ($request->session()->get('criteriumRadios') === "2")
			{
				$answer_id = Answer::where('votings_id', '=' ,$last_votings_id)
								   ->where('answer', '=', $request->session()->get('opcija'))
								   ->get()->first();

				DB::table('voting_success')->insert([
					'answer_id' => $answer_id->id,
					'relation' => $request->session()->get('relacija'),
					'value' => $request->session()->get('vrednost'),
					'voting_id' => $last_votings_id
				]);
			}
			else
			{
				DB::table('voting_success')->insert([
					'relation' => $request->session()->get('relacija'),
					'value' => $request->session()->get('vrednost'),
					'voting_id' => $last_votings_id
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
			$request->session()->forget('odg');
			$request->session()->forget('glasaci');
			$request->session()->forget('vise_odg');
			$request->session()->forget('relacija');
			$request->session()->forget('vrednost');
			$request->session()->forget('criteriumRadios');
			$request->session()->forget('opcija');
			$request->session()->forget('minimum');
			$request->session()->forget('maximum');

			Session::flash('vote_success', 'Uspešno je kreirano glasanje.');
			Session::flash('count', '1');

			return $this->getInitiatorHome();
		}
		else
		{
			$request['naslov'] = $request->session()->get('naslov');
			$request['opis'] = $request->session()->get('opis');
			$request['vreme1'] = $request->session()->get('vreme1');
			$request['vreme2'] = $request->session()->get('vreme2');
			$request['odg'] = $request->session()->get('odg');
			$request['glasaci'] = $request->session()->get('glasaci');
			if ($request->session()->get('vise_odg'))
				$request['vise_odg'] = true;

			$request['relacija'] = $request->session()->get('relacija');
			$request['vrednost'] = $request->session()->get('vrednost');
			$request['opcija'] = $request->session()->get('opcija');
			$request['criteriumRadios'] = $request->session()->get('criteriumRadios');
			$request['minimum'] = $request->session()->get('minimum');
			$request['maximum'] = $request->session()->get('maximum');

			$request->session()->forget('naslov');
			$request->session()->forget('opis');
			$request->session()->forget('vreme1');
			$request->session()->forget('vreme2');
			$request->session()->forget('odg');
			$request->session()->forget('glasaci');
			$request->session()->forget('vise_odg');
			$request->session()->forget('relacija');
			$request->session()->forget('vrednost');
			$request->session()->forget('criteriumRadios');
			$request->session()->forget('opcija');
			$request->session()->forget('minimum');
			$request->session()->forget('maximum');

			return redirect()->back()->withInput();
		}	
	}

	public function getVotingInfo($votings_id)
	{
		$datenow = Carbon::now();
		$voting = Voting::where('id', '=', $votings_id)
						->where('from', '<=', $datenow)
						->where('to', '>=', $datenow)
						->get();

		if ($voting->isEmpty())
		{
			return redirect('error')->with('fail', 'Nije validan zahtev!');
		}
		$datenow = str_replace(' ', 'T', $datenow);
		$voting = $voting[0];
		$start = new \Moment\Moment($voting->from);
		$duration = $start->from($voting->to);
		$duration_now = $start->from($datenow);
		$duration_days = $duration->getDays();
		$duration_now_days = $duration_now->getDays();
		if ($duration_days == 0)
		{
			$duration_now_days = $duration_now->getMinutes();
			$duration_days = $duration->getMinutes();
		}
		$progress = ($duration_now_days / $duration_days) * 100;
		
		return view('initiator.voting', compact('voting', 'progress'));
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
		$votings_id = Ticket::find($request['ticket'])->votings_id;
		
		if (! Voting::find($votings_id)->multiple_answers)
		{
			$this->validate($request, [
				'optionsRadios' => 'required'
	        ]);

	        //	save answer and check if already answered, if so then just modify it
			$answered = DB::table('answers_tickets')->where('tickets_id', '=', $request['ticket'])
													->count();

			if ($answered > 0)
			{
				DB::table('answers_tickets')->where('tickets_id', '=', $request['ticket'])
											->update([
												'answers_id' => $request['optionsRadios']
											]);
			}
			else
			{
				DB::table('answers_tickets')->insert([
					'answers_id' => $request['optionsRadios'],
				    'tickets_id' => $request['ticket']
				]);
			}
		}
		else
		{
			$this->validate($request, [
				'optionsCheckbox' => 'required'
	        ]);

	        $answered = DB::table('answers_tickets')->where('tickets_id', '=', $request['ticket'])
													->count();

			if ($answered > 0)
			{
				DB::table('answers_tickets')->where('tickets_id', '=', $request['ticket'])
											->delete();
			}
				
	        foreach ($request['optionsCheckbox'] as $option) {
	        	DB::table('answers_tickets')->insert([
					'answers_id' => $option,
				    'tickets_id' => $request['ticket']
				]);
	        }
		}
		
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

	public function deleteVoting(Request $request)
	{
		if ($request->ajax())
		{
			$tickets = Ticket::where('votings_id', '=', $request['voting_id'])->get();

			foreach ($tickets as $ticket) {
				DB::table('answers_tickets')->where('tickets_id', '=', $ticket->id)
										->delete();
			}
			
			Ticket::where('votings_id', '=', $request['voting_id'])
								->delete();

			DB::table('voting_success')->where('voting_id', '=', $request['voting_id'])
								->delete();

			Answer::where('votings_id', '=', $request['voting_id'])
								->delete();
			
			Voting::where('id', '=', $request['voting_id'])
						->delete();
		}
	}
}
