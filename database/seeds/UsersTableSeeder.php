<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
    	
        DB::table('users')->insert([
        	'firstname' => 'Nada',
        	'lastname' => 'Janković',
        	'email' => 'jankovicna@gmail.com',
        	'password' => bcrypt('admin'),
        	'is_admin' => true,
        	'is_initiator' => true,
            'active' => true,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
