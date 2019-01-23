<?php

namespace App\GraphQL\Query;

use App\File;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class FilesQuery extends Query
{
  protected $attributes = [
    'name' => 'files'
  ];

  public function type()
  {
    return Type::listOf(GraphQL::type('File'));
  }

  public function args()
  {
    return [
      'id' => [
        'name' => 'id',
        'type' => Type::int(),
      ],
      'project_id' => [
        'name' => 'project_id',
        'type' => Type::int(),
      ],
    ];
  }

  public function resolve($root, $args, $context, ResolveInfo $info)
  {
    $files = File::query();

    if (isset($args['id'])) {
      $files->where('id', $arg['id']);
    }

    if (isset($args['project_id'])) {
      $files->where('project_id', $args['project_id']);
    }

    $fields = $info->getFieldSelection();
    foreach ($fields as $field => $keys) {
        if ($field === 'project') {
            $files->with('project');
        }
    }

    return $files->latest()->get();
  }
}