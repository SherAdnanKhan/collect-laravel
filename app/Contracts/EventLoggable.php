<?php

namespace App\Contracts;

interface EventLoggable
{
    public function getIdentifier(): string;
    public function getTypeLabel(): string;
    public function getType(): string;
    public function getProject();
}
