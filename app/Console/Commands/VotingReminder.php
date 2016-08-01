<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SendGrid;
use Carbon\Carbon;
use App\Voting;

class VotingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voting:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'If reminder for voters is set, send e-mail at specified time.';

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
        $date_now = Carbon::now();
        $votings = Voting::where('reminder_time', '<=', $date_now)
                            ->get();

        foreach ($votings as $voting) 
        {
            $sendgrid = new SendGrid('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');
            $email = new SendGrid\Email();
       
            $m = "<p>Glasanje - " . $voting->name . " se završava " 
            . date('d-m-Y H:i:s', $voting->to) 
            . ".</p>";
       
            $email
                ->addTo($glasac)
                ->setFrom('votingsystemetf@gmail.com')
                ->setSubject('e-Glasanje: Podsetnik za ' . $voting->name)
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
            Voting::where('id', '=', $voting->id)
                    ->update(array('reminder_time' => null)); 
        }
    }
}
