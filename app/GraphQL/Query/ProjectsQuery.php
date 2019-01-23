<?php

namespace App\GraphQL\Query;

use App\Project;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class ProjectsQuery extends Query
{
    protected $attributes = [
        'name' => 'projects'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Project'));
    }

    public function args()
    {
        return [
      'id' => [
        'name' => 'id',
        'type' => Type::int(),
      ],
    ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $projects = Project::query();

        if (isset($args['id'])) {
            $projects->where('id', $args['id']);
        }

        $fields = $info->getFieldSelection();
        foreach ($fields as $field => $keys) {
            if ($field === 'files') {
                $projects->with('files');
            }

            if ($field === 'songs') {
                $projects->with('songs');
            }
        }

        return $projects->latest()->get();
    }
}
