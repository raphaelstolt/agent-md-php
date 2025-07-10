<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stolt\AgentMd\Commands\InfoCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class InfoCommandTest extends TestCase
{
    #[Test]
    public function itDisplaysInfoAsExpected()
    {
        $command = new InfoCommand();
        $tester = new CommandTester($command);

        $tester->execute(['file' => \realpath(\dirname(__FILE__, 2) . '/fixtures/AGENT.md'), '--format' => 'json']);

        $this->assertStringContainsString('SupportBot', $tester->getDisplay());
        $this->assertTrue($tester->getStatusCode() === Command::SUCCESS);
    }
}
