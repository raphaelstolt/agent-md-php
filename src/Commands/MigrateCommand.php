<?php

declare(strict_types=1);

namespace Stolt\AgentMd\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class MigrateCommand extends Command
{
    protected static $defaultName = 'migrate';

    protected array $legacyFiles = [
        'CLAUDE.md',
        '.clauderules',
        '.cursorrules',
        '.cursor/rules',
        '.clinerules',
        '.windsurfrules',
        '.replit.md',
        '.github/copilot-instructions.md',
    ];

    protected function configure(): void
    {
        $this->setName(MigrateCommand::$defaultName)
            ->setDescription('Migrate legacy agent config files to AGENT.md')
            ->addArgument('target', InputArgument::OPTIONAL, 'Directory to search (default: ' . \getcwd() . ')')
            ->setHelp(
                <<<HELP
Searches for legacy agent configuration files in the specified directory and migrates them to the standard AGENT.md format.

The following legacy files are supported:

- CLAUDE.md
- .clauderules
- .cursorrules
- .cursor/rules
- .clinerules
- .windsurfrules
- .replit.md
- .github/copilot-instructions.md

For each matching file:
  1. If AGENT.md does not already exist, the file is moved and renamed to AGENT.md
  2. If AGENT.md does exist, the legacy file is removed
  3. A symbolic link is created from the legacy file path to AGENT.md
HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dir = $input->getArgument('target') ?: \getcwd();
        $migrated = false;

        foreach ($this->legacyFiles as $legacy) {
            $orig = $dir . DIRECTORY_SEPARATOR . $legacy;
            if (!\file_exists($orig)) {
                continue;
            }

            $agentMd = $dir . DIRECTORY_SEPARATOR . 'AGENT.md';
            if (!\file_exists($agentMd)) {
                \rename($orig, $agentMd);
                $output->writeln("<info>Moved:</info> $legacy → AGENT.md");
            } else {
                \unlink($orig);
                $output->writeln("<comment>Removed legacy file:</comment> $legacy (AGENT.md already exists)");
            }

            \symlink('AGENT.md', $orig);
            $output->writeln("<info>Linked:</info> $legacy → AGENT.md");
            $migrated = true;
        }

        if (!$migrated) {
            $output->writeln('<comment>No legacy config files found to migrate.</comment>');
            return Command::SUCCESS;
        }

        $output->writeln('<info>✅ Configuration file migration complete.</info>');
        return Command::SUCCESS;
    }
}
