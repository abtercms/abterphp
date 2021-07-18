<?php

declare(strict_types=1);

namespace AbterPhp\Files\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use DateTime;
use DateTimeInterface;

class File implements IStringerEntity
{
    public const DATE_FORMAT = 'Y-m-d';

    protected string $id;

    protected string $filesystemName;

    protected string $oldFilesystemName;

    protected string $publicName;

    protected string $mime;

    protected string $description;

    protected DateTime $uploadedAt;

    protected ?FileCategory $category;

    protected bool $writable;

    protected ?string $content;

    /**
     * File constructor.
     *
     * @param string            $id
     * @param string            $filesystemName
     * @param string            $publicName
     * @param string            $mime
     * @param string            $description
     * @param FileCategory|null $category
     * @param DateTime|null     $uploadedAt
     * @param bool              $writable
     * @param string|null       $content
     *
     * @throws \Exception
     */
    public function __construct(
        string $id,
        string $filesystemName,
        string $publicName,
        string $mime,
        string $description,
        FileCategory $category = null,
        DateTime $uploadedAt = null,
        bool $writable = false,
        ?string $content = null
    ) {
        $this->id                = $id;
        $this->filesystemName    = $filesystemName;
        $this->oldFilesystemName = $filesystemName;
        $this->publicName        = $publicName;
        $this->mime              = $mime;
        $this->description       = $description;
        $this->category          = $category;
        $this->uploadedAt        = $uploadedAt ?: new DateTime();
        $this->writable          = $writable;
        $this->content           = $content;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFilesystemName(): string
    {
        return $this->filesystemName;
    }

    /**
     * @param string $filesystemName
     *
     * @return $this
     */
    public function setFilesystemName(string $filesystemName): static
    {
        $this->filesystemName = $filesystemName;

        return $this;
    }

    /**
     * @return string
     */
    public function getOldFilesystemName(): string
    {
        return $this->oldFilesystemName;
    }

    /**
     * @return bool
     */
    public function isFileUploaded(): bool
    {
        return $this->oldFilesystemName !== $this->filesystemName;
    }

    /**
     * @return string
     */
    public function getPublicName(): string
    {
        return $this->publicName;
    }

    /**
     * @param string $publicName
     *
     * @return $this
     */
    public function setPublicName(string $publicName): static
    {
        $this->publicName = $publicName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMime(): string
    {
        return $this->mime;
    }

    /**
     * @param string $mime
     *
     * @return $this
     */
    public function setMime(string $mime): static
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): File
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return FileCategory|null
     */
    public function getCategory(): ?FileCategory
    {
        return $this->category;
    }

    /**
     * @param FileCategory|null $category
     *
     * @return $this
     */
    public function setCategory(?FileCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUploadedAt(): DateTime
    {
        return $this->uploadedAt;
    }

    /**
     * @param DateTime $uploadedAt
     *
     * @return $this
     */
    public function setUploadedAt(DateTime $uploadedAt): static
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * @param bool $writable
     *
     * @return $this
     */
    public function setWritable(bool $writable): static
    {
        $this->writable = $writable;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasContent(): bool
    {
        return !($this->content === null);
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     *
     * @return $this
     */
    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if (!$this->publicName) {
            return '#' . $this->getId();
        }

        return $this->publicName;
    }

    /**
     * @return array|null
     */
    public function toData(): ?array
    {
        $data = [
            'id'          => $this->getId(),
            'name'        => $this->getPublicName(),
            'mime'        => $this->getMime(),
            'description' => $this->getDescription(),
            'category_id' => $this->getCategory() ? $this->getCategory()->getId() : null,
            'uploaded_at' => $this->getUploadedAt()->format(DateTimeInterface::ISO8601),
        ];

        if ($this->hasContent()) {
            $data['data'] = $this->getContent();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this->toData());
    }
}
