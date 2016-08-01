<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Hash;
use App\User;
use App\Voting;
use App\Ticket;
use App\Answer;
use Session;
use Carbon\Carbon;
use DB;

class VoterController extends Controller
{
    public function getVoterHome()
	{
		$datenow = Carbon::now();
		$votings = Voting::where('from', '<=', $datenow)
							->where('to', '>=', $datenow)
							->simplePaginate(5, ['*'], 'page_current');		
		$temp_current = Voting::where('from', '<=', $datenow)
						->where('to', '>=', $datenow)->get();
		$votings_past = Voting::where('to', '<', $datenow)
							->simplePaginate(5, ['*'], 'page_past');
		$temp_past = Voting::where('to', '<', $datenow)
							->get();

		$datenow = str_replace(' ', 'T', $datenow);

		// setting progresses for current votings
		for($i = 0; $i < count($temp_current); $i++)
		{
			$start = new \Moment\Moment($temp_current[$i]->from);
			$duration = $start->from($temp_current[$i]->to);
			$duration_now = $start->from($datenow);
			$duration_days = $duration->getDays();
			$duration_now_days = $duration_now->getDays();
			if ($duration_days == 0)
			{
				$duration_now_days = $duration_now->getMinutes();
				$duration_days = $duration->getMinutes();
			}
			$progresses[$i] = ($duration_now_days / $duration_days) * 100;
		}

		for ($i = 0; $i < count($temp_past); $i++)
		{
			$answers = Answer::where('votings_id', '=', $temp_past[$i]->id)->get();
			$past_answers[$i] = '';
			$num_voters = Ticket::where('votings_id', '=', $temp_past[$i]->id)->count();
			foreach ($answers as $answer) 
			{
				$past_answers[$i] = $past_answers[$i] . $answer->answer . ": ";

				$num_voted = Ticket::join('answers_tickets', 'tickets.id', '=', 'answers_tickets.tickets_id')
						->where('tickets.votings_id', '=', $temp_past[$i]->id)
						->where('answers_tickets.answers_id', '=', $answer->id)
						->distinct()
						->count();
				$percentage_ans = ($num_voted / $num_voters) * 100;
				$past_answers[$i] = $past_answers[$i] . $num_voted . " (" . $percentage_ans . "%)"
				. "\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";							
			}
			$past_answers[$i] = nl2br($past_answers[$i]);
		}

		$past_successes = array();
		for ($i = 0; $i < count($temp_past); $i++)
		{
			$temp = DB::table('voting_success')->where('voting_id', '=', $temp_past[$i]->id)
															->get();
			$past_successes[$i] = $temp[0];
		}

		return view('voter.home', compact('votings', 'progresses', 'votings_past',
									'past_answers', 'past_successes'));
	}

	public function getChangePassword()
	{
		return view('voter.changepassword');
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

		return view('voter.voting', compact('voting', 'progress'));
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

	public function getVote($ticket_id)
	{
		//Session::forget('vote_success');
		$votings_id = Ticket::where('id', '=', $ticket_id)->get();
		if ($votings_id->isEmpty())
			return redirect('error')->with('fail', 'Nije validan zahtev!');

		$voting = Voting::where('id','=', $votings_id->first()->votings_id)->get()->first();
		$answers = Answer::where('votings_id', '=', $votings_id->first()->votings_id)->get();
		
		return view('voter.vote', compact('ticket_id', 'answers', 'voting'));
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
			$ticket = Ticket::where('id', '=', $request['ticket'])->get()->first();
			$voting = Voting::where('id', '=', $ticket->votings_id)->get()->first();
			
			$this->validate($request, [
				'optionsCheckbox' => 'required|between:' . $voting->min . ',' . $voting->max
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
			
		$request->session()->flash('vote_success', 'Uspešno ste glasali!');
		$request->session()->flash('count', '1');
		return redirect()->route('voter.home');
	}
}
