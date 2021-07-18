<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Bootstrappers\Database;

use AbterPhp\Framework\Constant\Env;
use AbterPhp\Framework\Environments\Environment;
use AbterPhp\Framework\Database\PDO\Reader;
use AbterPhp\Framework\Database\PDO\Writer;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use PDO;

class PDOBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    protected const DEFAULT_CHARSET   = 'utf8';
    protected const DEFAULT_COLLATION = 'utf8mb4_unicode_ci';

    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return [
            Reader::class,
            Writer::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container): void
    {
        $reader = $this->createReader();
        $writer = $this->createWriter();

        $container->bindInstance(Reader::class, $reader);
        $container->bindInstance(Writer::class, $writer);
    }

    protected function createReader(): Reader
    {
        $dsn = sprintf(
            '%s:host=%s;dbname=%s;port=%s;charset=%s;collation=%s',
            Environment::mustGetVar(Env::PDO_READ_TYPE),
            Environment::mustGetVar(Env::PDO_READ_HOST),
            Environment::mustGetVar(Env::PDO_READ_DATABASE),
            Environment::mustGetVar(Env::PDO_READ_PORT),
            Environment::mustGetVar(Env::PDO_READ_CHARSET, static::DEFAULT_CHARSET),
            Environment::mustGetVar(Env::PDO_READ_COLLATION, static::DEFAULT_COLLATION)
        );

        $username  = Environment::mustGetVar(Env::PDO_READ_USERNAME);
        $password  = Environment::mustGetVar(Env::PDO_READ_PASSWORD);
        $options   = json_decode(Environment::mustGetVar(Env::PDO_READ_OPTIONS, '[]'));
        $errorMode = (int)Environment::mustGetVar(Env::PDO_READ_ERROR_MODE, (string)PDO::ERRMODE_EXCEPTION);

        $options[PDO::ATTR_ERRMODE] = $errorMode;

        $pdo = new PDO($dsn, $username, $password, $options);

        $reader = new Reader($pdo);
        $reader->setDialect(Environment::mustGetVar(Env::PDO_READ_TYPE));

        $this->init($pdo, Environment::getVar(Env::PDO_READ_COMMANDS, ''));

        return $reader;
    }

    protected function createWriter(): Writer
    {
        $dsn = sprintf(
            '%s:host=%s;dbname=%s;port=%s;charset=%s;collation=%s',
            Environment::mustGetVar(Env::PDO_WRITE_TYPE),
            Environment::mustGetVar(Env::PDO_WRITE_HOST),
            Environment::mustGetVar(Env::PDO_WRITE_DATABASE),
            Environment::mustGetVar(Env::PDO_WRITE_PORT),
            Environment::mustGetVar(Env::PDO_WRITE_CHARSET, static::DEFAULT_CHARSET),
            Environment::mustGetVar(Env::PDO_WRITE_COLLATION, static::DEFAULT_COLLATION)
        );

        $username  = Environment::mustGetVar(Env::PDO_WRITE_USERNAME);
        $password  = Environment::mustGetVar(Env::PDO_WRITE_PASSWORD);
        $options   = json_decode(Environment::mustGetVar(Env::PDO_WRITE_OPTIONS, '[]'));
        $errorMode = (int)Environment::mustGetVar(Env::PDO_WRITE_ERROR_MODE, (string)PDO::ERRMODE_EXCEPTION);

        $options[PDO::ATTR_ERRMODE] = $errorMode;

        $pdo = new PDO($dsn, $username, $password, $options);

        $writer = new Writer($pdo);
        $writer->setDialect(Environment::mustGetVar(Env::PDO_WRITE_TYPE));

        $this->init($pdo, Environment::getVar(Env::PDO_WRITE_COMMANDS, ''));

        return $writer;
    }

    /**
     * @param PDO    $pdo
     * @param string $rawCommands
     */
    protected function init(PDO $pdo, string $rawCommands): void
    {
        if (!$rawCommands) {
            return;
        }

        $commands = explode(';', $rawCommands);
        foreach ($commands as $command) {
            $pdo->exec($command);
        }
    }
}
