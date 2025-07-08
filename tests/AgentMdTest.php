<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stolt\AgentMd\AgentMd;

final class AgentMdTest extends TestCase
{
    #[Test]
    public function parsesAgentFile()
    {
        $agent = new AgentMd(__DIR__ . '/../tests/fixtures/AGENT.md');

        $this->assertEquals('SupportBot', \trim($agent->getName()));
        $this->assertStringContainsString('polite and professional', $agent->getPersona());
        $this->assertContains('Web Search', $agent->getTools());
    }

    #[Test]
    public function itAcceptsMarkdownString()
    {
        $markdown = "# Name\nAgent\n## Tools\n- WebSearch";
        $agent = new AgentMd($markdown);

        $this->assertEquals('Agent', $agent->getName());
        $this->assertEquals('WebSearch', $agent->getTools()[0]);
    }

    #[Test]
    public function itAcceptsMarkdownFile()
    {
        $file = __DIR__ . '/fixtures/sample-agent.md';
        \file_put_contents($file, "# Name\nFileAgent");

        $agent = new AgentMd($file);
        $this->assertEquals('FileAgent', $agent->getSection('name'));

        \unlink($file);
    }
}
