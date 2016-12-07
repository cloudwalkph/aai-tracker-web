<?php

use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\UserType::create([
            'type'  => 'admin',
            'label' => 'Admin',
            'description'    => 'Main Admin'
        ]);

        \App\Models\UserType::create([
            'type'  => 'client',
            'label' => 'Client',
            'description'    => 'Client of the event'
        ]);

        \App\Models\UserType::create([
            'type'  => 'sampler',
            'label' => 'Sampler',
            'description'    => 'Samplers'
        ]);
    }
}
