<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        
        DB::table('roles')->insert(['name' => 'Administrator']);
        DB::table('roles')->insert(['name' => 'Initiator']);
        DB::table('roles')->insert(['name' => 'Voter']);
    }
}
