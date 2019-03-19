<?php

namespace App\Traits;

trait EventLogged
{
    /**
     * Get the type of the resource as a lowercase string.
     *
     * @return string
     */
    public function getType(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    /**
     * The user readable version of the type.
     *
     * @return string
     */
    public function getTypeLabel(): string
    {
        return ucfirst($this->getType());
    }

    /**
     * Get the project
     *
     * @return Project|null
     */
    public function getProject()
    {
        if (!method_exists($this, 'project')) {
            return null;
        }

        return $this->project()->get();
    }

    /**
     * Get the label/string identifier for the event log.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->name;
    }
}
