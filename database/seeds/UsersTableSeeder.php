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
            'user_type_id'      => 1,
            'first_name'        => 'admin',
            'last_name'         => 'insite',
            'company'           => 'test company',
            'contact_number'    => '123123123',
            'email'             => 'admin@insite.com',
            'password'          => bcrypt('password'),
        ]);

        \App\Models\EventUser::create([
            'user_id'   => $user->id,
            'event_id'  => 1
        ]);

        $user = \App\User::create([
            'user_type_id'      => 2,
            'first_name'        => 'client',
            'last_name'         => 'insite',
            'company'           => 'test company',
            'contact_number'    => '123123123',
            'email'             => 'sampler@insite.com',
            'password'          => bcrypt('password'),
        ]);

        \App\Models\EventUser::create([
            'user_id'   => $user->id,
            'event_id'  => 1
        ]);

        $user = \App\User::create([
            'user_type_id'      => 3,
            'first_name'        => 'sampler',
            'last_name'         => 'insite',
            'company'           => 'test company',
            'contact_number'    => '123123123',
            'email'             => 'sampler@insite.com',
            'password'          => bcrypt('password'),
        ]);

        \App\Models\EventUser::create([
            'user_id'   => $user->id,
            'event_id'  => 1
        ]);
    }
}
