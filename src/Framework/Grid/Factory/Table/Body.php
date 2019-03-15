<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Factory\Table;

use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Collection\Body as Component;
use AbterPhp\Framework\I18n\ITranslator;

class Body
{
    /**
     * Body constructor.
     *
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param array        $getters
     * @param array        $rowArguments
     * @param Actions|null $rowActions
     *
     * @return Component
     */
    public function create(
        array $getters,
        array $rowArguments,
        ?Actions $rowActions
    ): Component {
        $tableBody = new Component($getters, $rowArguments, $rowActions, $this->translator);

        return $tableBody;
    }
}
