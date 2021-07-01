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
            Environment::mustGetVar(Env::PDO_READ_CHARSET),
            Environment::mustGetVar(Env::PDO_READ_COLLATION)
        );

        $username  = Environment::mustGetVar(Env::PDO_READ_USERNAME);
        $password  = Environment::mustGetVar(Env::PDO_READ_PASSWORD);
        $options   = Environment::mustGetVar(Env::PDO_READ_OPTIONS);
        $errorMode = Environment::mustGetVar(Env::PDO_READ_ERROR_MODE);

        $options = $options ? [] : json_decode($options);

        $commands = explode(';', Environment::mustGetVar(Env::PDO_READ_COMMANDS));

        $options[PDO::ATTR_ERRMODE] = $errorMode;

        $pdo = new PDO($dsn, $username, $password, $options);

        $reader = new Reader($pdo);
        $reader->setDialect(Environment::mustGetVar(Env::PDO_READ_TYPE));

        foreach ($commands as $command) {
            $pdo->exec($command);
        }

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
            Environment::mustGetVar(Env::PDO_WRITE_CHARSET),
            Environment::mustGetVar(Env::PDO_WRITE_COLLATION)
        );

        $username  = Environment::mustGetVar(Env::PDO_WRITE_USERNAME);
        $password  = Environment::mustGetVar(Env::PDO_WRITE_PASSWORD);
        $options   = Environment::mustGetVar(Env::PDO_WRITE_OPTIONS);
        $errorMode = Environment::mustGetVar(Env::PDO_WRITE_ERROR_MODE);

        $options = $options ? [] : json_decode($options);

        $commands = explode(';', Environment::mustGetVar(Env::PDO_WRITE_COMMANDS));

        $options[PDO::ATTR_ERRMODE] = $errorMode;

        $pdo = new PDO($dsn, $username, $password, $options);

        $writer = new Writer($pdo);
        $writer->setDialect(Environment::mustGetVar(Env::PDO_WRITE_TYPE));

        foreach ($commands as $command) {
            $pdo->exec($command);
        }

        return $writer;
    }
}
