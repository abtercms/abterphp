<?php

declare(strict_types=1);

namespace AbterPhp\Files\Domain\Entities;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use DateTime;

class FileDownload implements IStringerEntity
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    protected string $id;

    protected File $file;

    protected User $user;

    protected DateTime $downloadedAt;

    /**
     * @param string        $id
     * @param File          $file
     * @param User          $user
     * @param DateTime|null $downloadedAt
     */
    public function __construct(string $id, File $file, User $user, DateTime $downloadedAt = null)
    {
        $this->id           = $id;
        $this->file         = $file;
        $this->user         = $user;
        $this->downloadedAt = $downloadedAt ?: new DateTime();
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
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return DateTime
     */
    public function getDownloadedAt(): DateTime
    {
        return $this->downloadedAt;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '#' . $this->getId();
    }

    /**
     * @return array|null
     */
    public function toData(): ?array
    {
        return [
            'id'            => $this->getId(),
            'file_id'       => $this->getFile()->getId(),
            'user_id'       => $this->getUser()->getId(),
            'downloaded_at' => $this->getDownloadedAt()->format(static::DATE_FORMAT),
        ];
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode([
            'id'            => $this->getId(),
            'file'          => $this->getFile()->toData(),
            'user'          => $this->getUser()->toData(),
            'downloaded_at' => $this->getDownloadedAt()->format(static::DATE_FORMAT),
        ]);
    }
}
