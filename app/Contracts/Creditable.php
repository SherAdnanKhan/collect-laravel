<?php

namespace App\Contracts;

interface Creditable
{
    public function getKey();
    public function getType(): string;
    public function getContributorRoleType(): string;
    public function getContributorReferenceKey(): string;
}
