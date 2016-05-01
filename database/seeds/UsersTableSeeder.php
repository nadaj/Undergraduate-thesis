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
    	
        $role_admin = App\Role::where('name', 'Administrator')->first();
        $role_initiator = App\Role::where('name', 'Initiator')->first();
        $role_voter = App\Role::where('name', 'Voter')->first();

        $department_rti = App\Department::where('name', 'Računarska tehnika i informatika')->first();
        
        $title_asistent = App\Title::where('name', 'Asistent')->first();
        $title_prof = App\Title::where('name', 'Redovni profesor')->first();

        $admin = new App\User();
        $admin->firstname = 'Nada';
        $admin->lastname = 'Janković';
        $admin->email = 'jankovicna@gmail.com';
        $admin->password = bcrypt('admin1');
        $admin->created_at = date("Y-m-d H:i:s");
        $admin->role_id = $role_admin->id;
        $admin->save();
        
        $voter = new App\User();
        $voter->firstname = 'Nada';
        $voter->lastname = 'Janković';
        $voter->email = 'nadajankovic2004@gmail.com';
        $voter->password = bcrypt('perapetrovic');
        $voter->created_at = date("Y-m-d H:i:s");
        $voter->department_id = $department_rti->id;
        $voter->title_id = $title_asistent->id;
        $voter->role_id = $role_voter->id;
        $voter->save();
        
        $initiator = new App\User();
        $initiator->firstname = 'Nada';
        $initiator->lastname = 'Janković';
        $initiator->email = 'nadajankovic2004@yahoo.com';
        $initiator->password = bcrypt('admin');
        $initiator->created_at = date("Y-m-d H:i:s");
        $initiator->department_id = $department_rti->id;
        $initiator->title_id = $title_prof->id;
        $initiator->role_id = $role_initiator->id;
        $initiator->save();
        
    }
}
