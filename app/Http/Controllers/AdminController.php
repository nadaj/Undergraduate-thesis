<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Hash;
use SendGrid;
use Illuminate\Support\Str;
use App\Voting;
use App\Answer;
use App\Ticket;
use DB;
use Carbon\Carbon;
use Moment\Moment;
use App\VotingSuccess;

class AdminController extends Controller
{
    public function getAdminHome()
	{
		$date_now = Carbon::now();
		$current_votings = Voting::where('from', '<=', $date_now)
						->where('to', '>=', $date_now)->simplePaginate(5, ['*'], 'page_current');		
		$past_votings = Voting::where('to', '<', $date_now)->simplePaginate(5, ['*'], 'page_past');			
		$temp_past = Voting::where('to', '<', $date_now)->get();
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
			$temp = VotingSuccess::where('voting_id', '=', $temp_past[$i]->id)
															->get();
			$past_successes[$i] = $temp[0];
		}

		return view('admin.home', compact('current_votings', 'past_votings', 'progresses',
									'past_answers', 'past_successes'));
	}

	public function getChangePassword()
	{
		return view('admin.changepassword');
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

		if ($request['old_password'] === $request['password'])
		{
			return redirect()->back()->with(['fail' => 'Nova lozinka je ista kao stara.']);
		}

		User::where('email', '=', $user->email)->update([
			'password' => bcrypt($request['password'])
		]);

		return redirect()->back()->with(['success' => 'Uspešno je promenjena lozinka.']);
	}

	public function getUsers()
	{
		$users = User::where('confirmed', '=', true)
					->where('id', '!=', Auth::user()->id)
					->get();
		$nousers = User::where('confirmed', '=', false)
						->where('confirmation_code', '=', null)
						->get();

		return view('admin.users', compact('users', 'nousers'));
	}

	public function changeActive(Request $request)
	{
		if ($request->ajax())
		{
			$user = User::where('email', '=', $request['id'])->get();
			if (! $user) return;
			if ($user->first()->active == true)
				User::where('email', '=', $request['id'])->update([
					'active' => false
				]);
			else
				User::where('email', '=', $request['id'])->update([
					'active' => true
				]);
		}
	}

	public function getPostojeci()
	{
		$users = User::where('confirmed', '=', true)->get();
		return $users;
	}

	public function getNeodobreni()
	{
		$users = User::where('confirmed', '=', false)->get();
		return $users;
	}

	public function addUser(Request $request)
	{
		if ($request->ajax())
		{
			$confirmation_code = Str::random(30);
			$password = Str::random(13);

	        $sendgrid = new SendGrid('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');
	        $email = new SendGrid\Email();
	       
	        $url = url('/register/verify/' . $confirmation_code);
	        $m = "<p>Vaši kredencijali:<br/>E-mail: ".$request['email']."<br/>Lozinka: ".$password
	        ."</p><p><a href='".$url."'>Kliknite ovde</a> 
	            kako biste aktivirali Vaš nalog.</p>";
	   
	        $email
	            ->addTo($request['email'])
	            ->setFrom('votingsystemetf@gmail.com')
	            ->setSubject('e-Glasanje: Potvrda registracije')
	            ->setHtml($m)
	        ;

	        try {
	            $sendgrid->send($email);
	            // update users table
		       	User::where('email', '=', $request['email'])->update([
				    'password' => bcrypt($password),
				    'confirmation_code' => $confirmation_code,
				    'role_id' => $request['role']
				]);

	        } catch(\SendGrid\Exception $e) {
	            echo $e->getCode();
	            foreach($e->getErrors() as $er) {
	                echo $er;
	            }
	        }
		}
	}

	public function changeRole(Request $request)
	{
		if ($request->ajax())
		{
			User::where('email', '=', $request['user'])->update([
				'role_id' => $request['role']
			]);
		}
	}
}
