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
        $user = \App\User::create([
            'first_name'        => 'insite',
            'last_name'         => 'insite',
            'company'           => 'test company',
            'contact_number'    => '123123123',
            'email'             => 'insite@test.com',
            'password'          => bcrypt('password'),
        ]);

        \App\EventUser::create([
            'user_id'   => $user->id,
            'event_ud'  => 1
        ]);
    }
}
