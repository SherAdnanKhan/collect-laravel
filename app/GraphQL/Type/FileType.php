<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class FileType extends GraphQLType
{
  protected $attributes = [
    'name' => 'File',
    'description' => 'A file'
  ];

  public function fields()
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::int()),
        'description' => 'The id of a file'
      ],
      'name' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'The name of a file'
      ],
      'size' => [
        'type' => Type::nonNull(Type::int()),
        'description' => 'The size of a file'
      ],
      'created_at' => [
        'type' => Type::string(),
        'description' => 'Date a was created'
      ],
      'updated_at' => [
        'type' => Type::string(),
        'description' => 'Date a was updated'
      ],
      'project' => [
        'type' => GraphQL::type('Project'),
        'description' => 'Project'
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
}