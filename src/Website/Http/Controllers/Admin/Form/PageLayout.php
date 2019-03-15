<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Form;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Form\Factory\PageLayout as FormFactory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use AbterPhp\Website\Orm\PageLayoutRepo as Repo;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class PageLayout extends FormAbstract
{
    const ENTITY_PLURAL   = 'pageLayouts';
    const ENTITY_SINGULAR = 'pageLayout';

    const ENTITY_TITLE_SINGULAR = 'pages:pageLayout';
    const ENTITY_TITLE_PLURAL   = 'pages:pageLayouts';

    /** @var AssetManager */
    protected $assetManager;

    /** @var string */
    protected $resource = 'page_layouts';

    /**
     * PageLayout constructor.
     *
     * @param FlashService $flashService
     * @param ITranslator  $translator
     * @param UrlGenerator $urlGenerator
     * @param Repo         $repo
     * @param ISession     $session
     * @param FormFactory  $formFactory
     * @param AssetManager $assetManager
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        Repo $repo,
        ISession $session,
        FormFactory $formFactory,
        AssetManager $assetManager
    ) {
        parent::__construct($flashService, $translator, $urlGenerator, $repo, $session, $formFactory);

        $this->assetManager = $assetManager;
    }

    /**
     * @param int|null $entityId
     *
     * @return Entity
     */
    protected function createEntity(int $entityId = null): IStringerEntity
    {
        return new Entity((int)$entityId, '', '', '');
    }

    /**
     * @param IStringerEntity|null $entity
     */
    protected function addCustomAssets(?IStringerEntity $entity = null)
    {
        parent::addCustomAssets($entity);

        if (!($entity instanceof Entity)) {
            return;
        }

        $footer = $this->getResourceName(static::RESOURCE_FOOTER);
        $this->assetManager->addJs($footer, '/admin-assets/js/hideable-container.js');
    }
}
