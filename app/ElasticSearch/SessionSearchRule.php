<?php

namespace App\ElasticSearch;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use ScoutElastic\SearchRule;

class SessionSearchRule extends SearchRule
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
        try {
            $searchDate = Carbon::parse($this->builder->query)->format('Y-m-d');
            return [
                'should' => [
                    ['query_string' => [
                        'query' => "*{$this->builder->query}*",
                        'fields' => ["name", "session_type", "venue"]
                    ]],
                    ['multi_match' => [
                        'query' => $searchDate,
                        'fields' => ["started_at", "ended_at"]
                    ]]
                ]
             ];
        } catch (\Exception $err) {
            return [
                'must' => [
                    ['query_string' => [
                        'query' => "*{$this->builder->query}*",
                        'fields' => ["name", "session_type", "venue"]
                    ]]
                ]
             ];
        }
    }
}
