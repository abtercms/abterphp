<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class BlockLayout extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('id')
            ->integer();

        $validator
            ->field('identifier')
            ->required();

        $validator
            ->field('body');

        return $validator;
    }
}
