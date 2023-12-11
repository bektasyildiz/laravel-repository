<?php

namespace Bektasyildiz\LaravelRepository;

use Illuminate\Database\Eloquent\Model;
use Bektasyildiz\LaravelRepository\Repositories\BaseRepository;

class LaravelRepository
{
    public function getRepository(Model $model)
    {
        return new BaseRepository($model);
    }
}
