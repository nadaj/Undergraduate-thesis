<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Voting;
use DB;
use Carbon\Carbon;

class VotingSuccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voting:success';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will check voting statuses.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // change statuses for finished votings
        $date_now = Carbon::now();
        $votings = Voting::where('to', '<', $date_now)
                            ->where('status', '=', 0)
                            ->get();
        foreach ($votings as $voting) 
        {
            // get criterium and check if it's true
            $voting_success = DB::table('voting_success')->where('voting_id', '=', $voting->id)
                                                         ->get();
            $voting_success = $voting_success[0];

            $success = 2;   //fail
            if ($voting_success->answer_id == null)     // number of people voted
            {
                $num_voted = DB::table('tickets')->join('answers_tickets', 'tickets.id', '=', 'answers_tickets.tickets_id')
                                    ->select('answers_tickets.tickets_id')
                                    ->where('tickets.votings_id', '=', $voting->id)
                                    ->distinct()
                                    ->count();
            }
            else
            {
                $num_voted = DB::table('tickets')->join('answers_tickets', 'tickets.id', '=', 'answers_tickets.tickets_id')
                                    ->select('answers_tickets.tickets_id')
                                    ->where('tickets.votings_id', '=', $voting->id)
                                    ->where('answers_tickets.answers_id', '=', $voting_success->answer_id)
                                    ->distinct()
                                    ->count();
            }

            switch ($voting_success->relation)
            {
                case "=":
                    if ($num_voted == $voting_success->value)
                        $success = 1; break;
                case "<>":
                    if ($num_voted != $voting_success->value)
                        $success = 1; break;
                case ">":
                    if ($num_voted > $voting_success->value)
                        $success = 1; break;
                case "<":
                    if ($num_voted < $voting_success->value)
                        $success = 1; break;
                case ">=":
                    if ($num_voted >= $voting_success->value)
                        $success = 1; break;
                case "<=":
                    if ($num_voted <= $voting_success->value)
                        $success = 1; break;
            }
            Voting::where('id', '=', $voting->id)->update(['status' => $success]);
        }
    }
}
