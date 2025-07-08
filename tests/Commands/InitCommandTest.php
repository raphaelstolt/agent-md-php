<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stolt\AgentMd\Commands\InitCommand;
use Symfony\Component\Console\Tester\CommandTester;

final class InitCommandTest extends TestCase
{
    #[Test]
    public function itCreatesAgentMdFile()
    {
        $file = \sys_get_temp_dir() . '/AGENT_test.md';
        @\unlink($file);

        $command = new InitCommand();
        $tester = new CommandTester($command);

        $tester->execute(['file' => $file]);

        $this->assertFileExists($file);
        $this->assertStringContainsString('ExampleAgent', \file_get_contents($file));

        \unlink($file);
    }
}
