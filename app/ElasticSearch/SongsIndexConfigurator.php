<?php

namespace App\ElasticSearch;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class SongsIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        //
    ];
}
