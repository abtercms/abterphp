<?php

namespace Integration\Admin\Console\User;

use AbterPhp\Admin\Console\Commands\User\Create;
use AbterPhp\Admin\Console\Commands\User\Delete;
use AbterPhp\Admin\Console\Commands\User\UpdatePassword;
use Integration\Framework\Console\IntegrationTestCase;

class RealUserCommandTest extends IntegrationTestCase
{
    /**
     * Tests calling the command with proper options
     */
    public function testAll()
    {
        $arguments = $this->getArguments();

        $this->command(Create::COMMAND_NAME)
            ->withArguments($arguments)
            ->withStyle(false)
            ->execute()
            ->assertResponse
            ->isOK()
            ->outputEquals(strip_tags(Create::COMMAND_SUCCESS) . PHP_EOL);

        $this->command(UpdatePassword::COMMAND_NAME)
            ->withArguments([$arguments[0], $arguments[2]])
            ->withStyle(false)
            ->execute()
            ->assertResponse
            ->isOK()
            ->outputEquals(strip_tags(UpdatePassword::COMMAND_SUCCESS)  . PHP_EOL);

        $this->command(Delete::COMMAND_NAME)
            ->withArguments($arguments[0])
            ->withStyle(false)
            ->execute()
            ->assertResponse
            ->isOK()
            ->outputEquals(strip_tags(Delete::COMMAND_SUCCESS)  . PHP_EOL);
    }

    /**
     * Tests calling the command with proper options
     */
    public function testUnsafeCreation()
    {
        $unsafeArguments = $this->getUnsafeArguments();

        $this->command(Create::COMMAND_NAME)
            ->withArguments($unsafeArguments)
            ->withStyle(false)
            ->execute()
            ->assertResponse
            ->isOK()
            ->outputEquals(strip_tags(Create::COMMAND_UNSAFE_PASSWORD) . PHP_EOL);
    }

    /**
     * Tests calling the command with proper options
     */
    public function testUnsafePasswordChange()
    {
        $arguments = $this->getArguments();

        $this->command(Create::COMMAND_NAME)
            ->withArguments($arguments)
            ->withStyle(false)
            ->execute()
            ->assertResponse
            ->isOK()
            ->outputEquals(strip_tags(Create::COMMAND_SUCCESS) . PHP_EOL);

        $unsafeArguments = $this->getUnsafeArguments();

        $this->command(UpdatePassword::COMMAND_NAME)
            ->withArguments([$unsafeArguments[0], $unsafeArguments[2]])
            ->withStyle(false)
            ->execute()
            ->assertResponse
            ->isOK()
            ->outputEquals(strip_tags(Create::COMMAND_UNSAFE_PASSWORD) . PHP_EOL);
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        $user = 'user' . rand(0, PHP_INT_MAX);

        return [$user, "$user@example.com", 'u5W3$yX2PfTuJFAY', 'admin'];
    }

    /**
     * @return array
     */
    protected function getUnsafeArguments(): array
    {
        $user = 'user' . rand(0, PHP_INT_MAX);

        return [$user, "$user@example.com", 'hello', 'admin'];
    }
}
