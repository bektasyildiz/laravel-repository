<?php

namespace Bektasyildiz\LaravelRepository;

use Illuminate\Support\Facades\Facade;

class LaravelRepositoryFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravelrepository';
    }
}
