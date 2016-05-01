<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('departments')->delete();

	    DB::table('departments')->insert(['name' => 'Elektronika']);
	    DB::table('departments')->insert(['name' => 'Elektroenergetski sistemi']);
	    DB::table('departments')->insert(['name' => 'Energetski pretvarači i pogoni']);
	    DB::table('departments')->insert(['name' => 'Mikroelektronika i tehnička fizika']);
	    DB::table('departments')->insert(['name' => 'Opšta elektrotehnika']);
	    DB::table('departments')->insert(['name' => 'Opšte obrazovanje']);
	    DB::table('departments')->insert(['name' => 'Primenjena matematika']);
	    DB::table('departments')->insert(['name' => 'Računarska tehnika i informatika']);
	    DB::table('departments')->insert(['name' => 'Signali i sistemi']);
	    DB::table('departments')->insert(['name' => 'Telekomunikacije']);
    }
}
