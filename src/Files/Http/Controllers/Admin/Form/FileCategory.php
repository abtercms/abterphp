<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Form;

use AbterPhp\Framework\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Files\Form\Factory\FileCategory as FormFactory;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Files\Orm\FileCategoryRepo as Repo;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class FileCategory extends FormAbstract
{
    const ENTITY_PLURAL   = 'fileCategories';
    const ENTITY_SINGULAR = 'fileCategory';

    const ENTITY_TITLE_SINGULAR = 'files:fileCategory';
    const ENTITY_TITLE_PLURAL   = 'files:fileCategories';

    /** @var string */
    protected $resource = 'file_categories';

    /**
     * FileCategory constructor.
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
    public function createEntity(int $entityId = null): IStringerEntity
    {
        $entity = new Entity((int)$entityId, '', '', false);

        return $entity;
    }
}
