<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;

interface Creditable
{
    public function credits();
    public function getKey();
    public function getType(): string;
    public function getContributorRoleType(): string;
    public function getContributorReferenceKey(): string;
}
