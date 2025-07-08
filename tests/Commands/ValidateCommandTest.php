<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stolt\AgentMd\Commands\ValidateCommand;
use Symfony\Component\Console\Tester\CommandTester;

final class ValidateCommandTest extends TestCase
{
    #[Test]
    public function itValidatesAgentFile()
    {
        $command = new ValidateCommand();
        $tester = new CommandTester($command);

        $tester->execute([
            'file' => \realpath(\dirname(__FILE__, 2) . '/fixtures/AGENT.md')
        ]);

        $this->assertStringContainsString('AGENT.md is valid', $tester->getDisplay());
    }

    #[Test]
    public function itDetectsMissingAgentFile()
    {
        $command = new ValidateCommand();
        $tester = new CommandTester($command);

        $tester->execute([
            'file' => 'nonexistent.md'
        ]);

        $this->assertSame(1, $tester->getStatusCode());
        $this->assertStringContainsString('Missing', $tester->getDisplay());
    }
}
