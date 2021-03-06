<?php

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Poll;
use App\Models\EventLocation;
use App\Models\EventPoll;

class EventsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [
            [
                'name'          => 'Magnum',
                'description'   => 'Selecta Magnum Event',
                'start_date'    => '2016-12-08',
                'end_date'      => '2016-12-30',
            ]
        ];

        $polls = [
            [
                'name'  => 'Age Group',
                'type'  => 'single-choice',
                'choices'   => [
                    [
                        'name'  => '15 - 20',
                        'slug'  => '15-20'
                    ],
                    [
                        'name'  => '21 - 25',
                        'slug'  => '21-25'
                    ],
                    [
                        'name'  => '26 - 30',
                        'slug'  => '26-30'
                    ],
                    [
                        'name'  => '31 - 35',
                        'slug'  => '31-35'
                    ]
                ]
            ],
            [
                'name'  => 'Gender',
                'type'  => 'single-choice',
                'choices'   => [
                    [
                        'name'  => 'Male',
                        'slug'  => 'male'
                    ],
                    [
                        'name'  => 'Female',
                        'slug'  => 'female'
                    ]
                ]
            ]
        ];

        foreach ($polls as $poll) {
            $poll['choices'] = json_encode($poll['choices']);
            Poll::create($poll);
        }

        foreach ($events as $event) {
            $eventData = Event::create($event);
            EventLocation::create([
                'event_id'  => $eventData->id,
                'name'  => 'SM Mall of Asia',
                'expected_hits' => 1000
            ]);

            EventLocation::create([
                'event_id'  => $eventData->id,
                'name'  => 'SM Megamall',
                'expected_hits' => 900
            ]);

            EventLocation::create([
                'event_id'  => $eventData->id,
                'name'  => 'SM Fairview',
                'expected_hits' => 500
            ]);

            EventLocation::create([
                'event_id'  => $eventData->id,
                'name'  => 'SM Makati',
                'expected_hits' => 500
            ]);

            EventLocation::create([
                'event_id'  => $eventData->id,
                'name'  => 'SM Parañaque',
                'expected_hits' => 500
            ]);
        }

        EventPoll::create([
            'event_id'  => 1,
            'poll_id'   => 1
        ]);

        EventPoll::create([
            'event_id'  => 1,
            'poll_id'   => 2
        ]);
    }
}
