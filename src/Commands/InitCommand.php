<?php

declare(strict_types=1);

namespace Stolt\AgentMd\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class InitCommand extends Command
{
    protected static $defaultName = 'init';

    protected function configure(): void
    {
        $this->setName(InitCommand::$defaultName)
            ->setDescription('Create a boilerplate AGENT.md file')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to AGENT.md file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');

        if (\file_exists($file)) {
            $output->writeln("File <info>$file</info> already exists");
            return Command::FAILURE;
        }

        $template = <<<MD
# Name
ExampleAgent

## Persona
An AI assistant that is helpful, concise, and informative.

## Capabilities
- Summarization
- Search
- Reasoning

## Tools
- Web Search
- Calculator
MD;

        \file_put_contents($file, $template);
        $output->writeln("AGENT.md created at <info>$file</info>");
        return Command::SUCCESS;
    }
}
