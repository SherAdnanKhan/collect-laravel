<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class ProjectType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Project',
        'description' => 'A project'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of a project'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of a project'
            ],
            'songs' => [
                'type' => Type::listOf(GraphQL::type('Song')),
                'description' => 'The project songs'
            ],
            'songs_count' => [
                'type' => Type::int(),
                'description' => 'The number of songs on a project'
            ],
            'files' => [
                'type' => Type::listOf(GraphQL::type('File')),
                'description' => 'The project files'
            ],
            'files_count' => [
                'type' => Type::int(),
                'description' => 'The number of files on a project'
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'Date a was created'
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'Date a was updated'
            ],
        ];
    }

    protected function resolveCreatedAtField($root, $args)
    {
        return (string) $root->created_at;
    }

    protected function resolveUpdatedAtField($root, $args)
    {
        return (string) $root->updated_at;
    }

    protected function resolveSongsCountField($root, $args)
    {
        return $root->songs->count();
    }

    protected function resolveFilesCountField($root, $args)
    {
        return $root->files->count();
    }
}
