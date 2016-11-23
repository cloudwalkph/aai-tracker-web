<?php
namespace App\AAI;

use App\AAI\Modules\EventAnswers\Repositories\EventAnswersRepository;
use App\AAI\Modules\EventLocations\Repositories\EventLocationsRepository;
use App\AAI\Modules\EventPolls\Repositories\EventPollsRepository;
use App\AAI\Modules\Events\Repositories\EventsRepository;
use App\AAI\Modules\Polls\Repositories\PollsRepository;
use App\AAI\Services\EventAnswersService;
use App\AAI\Services\EventsService;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\EventLocation;
use App\Models\EventPoll;
use App\Models\Poll;
use Illuminate\Support\ServiceProvider;

class AAIServiceProvider extends ServiceProvider
{

    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('aai', function($app) {
            return new AAI($app['files'], $app);
        });

        $this->app->booting(function ($app) {
            $app['aai']->register();
        });

        $this->bindServices();
    }

    public function bindServices()
    {
        $this->app->bind('App\AAI\Services\EventsService', function($app) {
            return new EventsService(new EventsRepository(new Event),
                new PollsRepository(new Poll), new EventLocationsRepository(new EventLocation),
                new EventPollsRepository(new EventPoll));
        });

        $this->app->bind('App\AAI\Services\EventAnswersService', function($app) {
            return new EventAnswersService(new EventAnswersRepository(new EventAnswer));
        });
    }
}