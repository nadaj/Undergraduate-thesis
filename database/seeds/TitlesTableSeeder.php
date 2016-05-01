<?php

use Illuminate\Database\Seeder;

class TitlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('titles')->delete();
    	
        DB::table('titles')->insert(['name' => 'Redovni profesor']);
	    DB::table('titles')->insert(['name' => 'Vanredni profesor']);
	    DB::table('titles')->insert(['name' => 'Docent']);
	    DB::table('titles')->insert(['name' => 'Asistent']);
	    DB::table('titles')->insert(['name' => 'Saradnik u nastavi']);
	    DB::table('titles')->insert(['name' => 'Gostujući profesor']);
    }
}
