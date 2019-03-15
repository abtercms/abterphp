<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Form;

use AbterPhp\Framework\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Files\Form\Factory\File as FormFactory;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Files\Orm\FileRepo as Repo;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class File extends FormAbstract
{
    const ENTITY_PLURAL   = 'files';
    const ENTITY_SINGULAR = 'file';

    const ENTITY_TITLE_SINGULAR = 'files:file';
    const ENTITY_TITLE_PLURAL   = 'files:files';

    /** @var string */
    protected $resource = 'files';

    /**
     * File constructor.
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
        $fileCategory = new FileCategory(0, '', '', false, []);

        return new Entity((int)$entityId, '', '', '', $fileCategory, null);
    }
}
