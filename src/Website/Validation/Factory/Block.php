<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class Block extends ValidatorFactory
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
            ->field('identifier');

        $validator
            ->field('title');

        // Body must not be empty if layout and layout ID are both empty
        $validator
            ->field('body')
            ->atLeastOne('layout_id', 'layout');

        $validator
            ->field('layout_id')
            ->integer()
            ->atLeastOne('layout');

        $validator
            ->field('layout');

        return $validator;
    }
}
