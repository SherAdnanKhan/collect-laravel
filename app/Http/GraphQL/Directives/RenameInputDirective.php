<?php

namespace App\Http\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Exceptions\DirectiveException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;

class RenameInputDirective extends BaseDirective implements FieldResolver
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'renameInput';
    }

    /**
     * Resolve the field directive.
     *
     * @param FieldValue $fieldValue
     *
     * @return FieldValue
     * @throws DirectiveException
     */
    public function resolveField(FieldValue $fieldValue): FieldValue
    {
        $attribute = $this->directiveArgValue('attribute');

        if (! $attribute) {
            throw new DirectiveException(
                "The [{$this->name()}] directive requires an `attribute` argument."
            );
        }

        return $fieldValue->setResolver(
            function ($rootValue, array $args) use ($attribute) {
                var_dump($rootValue);
                die();
            }
        );
    }
}
