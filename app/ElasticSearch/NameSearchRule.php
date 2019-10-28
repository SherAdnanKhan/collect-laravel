<?php

namespace App\ElasticSearch;

use ScoutElastic\SearchRule;

class NameSearchRule extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        //
    }

    /**
     * @inheritdoc
     */
    public function buildQueryPayload()
    {
        return [
            'must' => [
                'query_string' => [
                    'query' => "*{$this->builder->query}*"
                ]
            ]
         ];
    }
}
