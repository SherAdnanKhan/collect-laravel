<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;

interface Creditable
{
    public function credits();
    public function getKey();
    public function getType(): string;
    public function getContributorRoleTypes($version): array;
    public function getContributorReferenceKey($version): string;
}
