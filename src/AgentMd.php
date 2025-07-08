<?php

declare(strict_types=1);

namespace Stolt\AgentMd;

final class AgentMd
{
    public readonly string $path;

    private array $sections = [];

    protected string $agentMarkdown;

    private array $requiredSections = ['name', 'persona', 'tools'];

    public function __construct(string $pathOrMarkdown)
    {
        $this->agentMarkdown = $pathOrMarkdown;

        if (\is_file($pathOrMarkdown) && \is_readable($pathOrMarkdown)) {
            $this->agentMarkdown = \file_get_contents($pathOrMarkdown);
            $this->path = $pathOrMarkdown;
        }

        $this->sections = $this->parseSections($this->agentMarkdown);
    }

    private function parseSections(string $markdown): array
    {
        $sections = [];
        \preg_match_all('/^#{1,6}\s*(.+)$/m', $markdown, $matches, PREG_OFFSET_CAPTURE);

        $headings = $matches[1];
        $positions = $matches[0];

        for ($i = 0; $i < \count($headings); $i++) {
            $title = \trim($headings[$i][0]);
            $titleKey = \strtolower($title);

            $start = \strpos($markdown, $positions[$i][0], (int)$positions[$i][1]);
            $start += \strlen($positions[$i][0]); // move past the heading line

            $end = isset($positions[$i + 1])
                ? \strpos($markdown, $positions[$i + 1][0], (int)$positions[$i + 1][1])
                : \strlen($markdown);

            $content = \trim(\substr($markdown, $start, $end - $start));
            $sections[$titleKey] = $content;
        }

        return $sections;
    }

    public function getSection(string $name): ?string
    {
        return $this->sections[\strtolower($name)] ?? null;
    }

    public function getName(): ?string
    {
        return $this->getSection('name');
    }

    public function getPersona(): ?string
    {
        return $this->getSection('persona');
    }

    public function getTools(): array
    {
        $sections = $this->parseSections($this->agentMarkdown);

        if (!isset($sections['tools'])) {
            return [];
        }

        $lines = \explode("\n", \trim($sections['tools']));

        $tools = \array_filter(\array_map(function ($line) {
            $line = \trim($line);
            $line = \preg_replace('/^[-*]\s+/', '', $line);
            $line = \preg_replace('/^\d+\.\s+/', '', $line);
            return \trim($line);
        }, $lines));

        return \array_values($tools);
    }

    public function getAllSections(): array
    {
        return $this->sections;
    }

    public function validate(): array
    {
        $errors = [];
        foreach ($this->requiredSections as $section) {
            if (!isset($this->sections[$section]) || \trim($this->sections[$section]) === '') {
                $errors[] = "Missing or empty required section: $section";
            }
        }
        return $errors;
    }

    public function setRequiredSections(array $sections): void
    {
        $this->requiredSections = \array_map('strtolower', $sections);
    }
}
