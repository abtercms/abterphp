<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Form;

use AbterPhp\Framework\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Form\Factory\BlockLayout as FormFactory;
use AbterPhp\Website\Domain\Entities\BlockLayout as Entity;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Orm\BlockLayoutRepo as Repo;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class BlockLayout extends FormAbstract
{
    const ENTITY_PLURAL   = 'blockLayouts';
    const ENTITY_SINGULAR = 'blockLayout';

    const ENTITY_TITLE_SINGULAR = 'pages:blockLayout';
    const ENTITY_TITLE_PLURAL   = 'pages:blockLayouts';

    /** @var string */
    protected $resource = 'block_layouts';

    /**
     * Layout constructor.
     *
     * @param FlashService $flashService
     * @param ITranslator  $translator
     * @param UrlGenerator $urlGenerator
     * @param Repo         $repo
     * @param ISession     $session
     * @param FormFactory  $formFactory
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        Repo $repo,
        ISession $session,
        FormFactory $formFactory
    ) {
        parent::__construct($flashService, $translator, $urlGenerator, $repo, $session, $formFactory);
    }

    /**
     * @param int|null $entityId
     *
     * @return Entity
     */
    protected function createEntity(int $entityId = null): IStringerEntity
    {
        return new Entity((int)$entityId, '', '');
    }
}
