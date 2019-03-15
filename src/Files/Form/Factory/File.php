<?php

declare(strict_types=1);

namespace AbterPhp\Files\Form\Factory;

use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Factory\Base;
use AbterPhp\Framework\Form\Factory\IFormFactory;
use AbterPhp\Framework\Form\Form;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Option;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Orm\FileCategoryRepo;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class File extends Base
{
    /** @var FileCategoryRepo */
    protected $fileCategoryRepo;

    /**
     * File constructor.
     *
     * @param ISession         $session
     * @param ITranslator      $translator
     * @param FileCategoryRepo $fileCategoryRepo
     */
    public function __construct(ISession $session, ITranslator $translator, FileCategoryRepo $fileCategoryRepo)
    {
        parent::__construct($session, $translator);

        $this->fileCategoryRepo = $fileCategoryRepo;
    }

    /**
     * @param string       $action
     * @param string       $method
     * @param string       $showUrl
     * @param IEntity|null $entity
     *
     * @return $this
     */
    public function create(string $action, string $method, string $showUrl, ?IEntity $entity = null): IForm
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(IFormFactory::ERR_MSG_ENTITY_MISSING);
        }

        $this->createForm($action, $method, true)
            ->addDefaultElements()
            ->addFile()
            ->addDescription($entity)
            ->addFileCategory($entity)
            ->addDefaultButtons($showUrl);

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @return $this
     */
    protected function addFile(): File
    {
        $input = new Input('file', 'file', '', null, [Input::ATTRIBUTE_TYPE => Input::TYPE_FILE]);
        $label = new Label('file', 'files:file', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDescription(Entity $entity): File
    {
        $input = new Textarea(
            'description',
            'description',
            $entity->getDescription()
        );
        $label = new Label('description', 'files:fileDescription', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addFileCategory(Entity $entity): File
    {
        $allFileCategories = $this->getAllFileCategories();
        $fileCategoryId   = (int)$entity->getCategory()->getId();

        $options = $this->createFileCategoryOptions($allFileCategories, $fileCategoryId);

        $this->form[] = new FormGroup(
            $this->createFileCategorySelect($entity, $options),
            $this->createFileCategoryLabel(),
            $this->createFileCategoryHelp($allFileCategories)
        );

        return $this;
    }

    /**
     * @return FileCategory[]
     */
    protected function getAllFileCategories(): array
    {
        return $this->fileCategoryRepo->getAll();
    }

    /**
     * @param FileCategory[] $allFileCategories
     * @param int            $fileCategoryId
     *
     * @return array
     */
    protected function createFileCategoryOptions(array $allFileCategories, int $fileCategoryId): array
    {
        $options = [];
        foreach ($allFileCategories as $fileCategory) {
            $attributes = [Option::ATTRIBUTE_VALUE => (string)$fileCategory->getId()];
            if ($fileCategory->getId() === $fileCategoryId) {
                $attributes[Option::ATTRIBUTE_SELECTED] = null;
            }
            $options[] = new Option($fileCategory->getName(), null, $attributes);
        }

        return $options;
    }

    /**
     * @param Entity   $entity
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createFileCategorySelect(Entity $entity, array $options): Select
    {
        $attributes = [
            Select::ATTRIBUTE_SIZE => $this->getMultiSelectSize(
                count($options),
                static::MULTISELECT_MIN_SIZE,
                static::MULTISELECT_MAX_SIZE
            ),
        ];

        $value = (string)$entity->getCategory()->getId();

        $select = new Select('category_id', 'category_id', $value, false, null, $attributes);

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }

    /**
     * @return Label
     */
    protected function createFileCategoryLabel(): Label
    {
        return new Label('file_category_id', 'files:fileCategory', null, [], $this->translator);
    }

    /**
     * @param array $allFileCategories
     *
     * @return Help|null
     */
    protected function createFileCategoryHelp(array $allFileCategories): ?Help
    {
        if (count($allFileCategories) > 0) {
            return null;
        }

        return new Help('form:noFileCategories');
    }

    /**
     * @param int $optionCount
     * @param int $minSize
     * @param int $maxSize
     *
     * @return int
     */
    protected function getMultiSelectSize(int $optionCount, int $minSize, int $maxSize): int
    {
        return (int)max(min($optionCount, $maxSize), $minSize);
    }
}
