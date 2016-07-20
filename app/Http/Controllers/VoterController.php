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

class VoterController extends Controller
{
    public function getVoterHome()
	{
		$datenow = Carbon::now();
		$votings = Voting::where('from', '<=', $datenow)
							->where('to', '>=', $datenow)
							->get();

		// setting progresses for current votings
		for($i = 0; $i < count($votings); $i++)
		{
			$start = new \Moment\Moment($votings[$i]->from);
			$duration = $start->from($votings[$i]->to);
			$duration_now = $start->fromNow();
			$duration_days = $duration->getDays();
			$duration_now_days = $duration_now->getDays();

			$progresses[$i] = ($duration_now_days / $duration_days) * 100;
		}

		return view('voter.home', compact('votings', 'progresses'));
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
		
		$voting = $voting[0];
		$start = new \Moment\Moment($voting->from);
		$duration = $start->from($voting->to);
		$duration_now = $start->fromNow();
		$duration_days = $duration->getDays();
		$duration_now_days = $duration_now->getDays();

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
		$this->validate($request, [
			'optionsRadios' => 'required'
        ]);

		//	save answer
		Ticket::where('id', '=', $request['ticket'])->update([
			'answers_id' => $request['optionsRadios']
		]);

		$request->session()->flash('vote_success', 'Uspešno ste glasali!');
		$request->session()->flash('count', '1');
		return redirect()->route('voter.home');
	}
}
