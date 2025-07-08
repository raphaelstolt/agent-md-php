<?php

declare(strict_types=1);

namespace Stolt\AgentMd\Commands;

use Stolt\AgentMd\AgentMd;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class InfoCommand extends Command
{
    protected static $defaultName = 'info';

    protected function configure(): void
    {
        $this->setName(InfoCommand::$defaultName)
            ->setDescription('Display parsed AGENT.md sections')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to AGENT.md file')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format: text or json', 'text');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('file');
        $format = $input->getOption('format');

        try {
            $agent = new AgentMd($path);
        } catch (\Throwable $e) {
            $output->writeln("<error>Error: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }

        if ($format === 'json') {
            $data = [
                'name' => $agent->getName(),
                'persona' => $agent->getPersona(),
                'tools' => $agent->getTools()
            ];
            $output->writeln(\json_encode($data, JSON_PRETTY_PRINT));
        } else {
            $output->writeln("<info>Name:</info> " . $agent->getName());
            $output->writeln("<info>Persona:</info>\n" . $agent->getPersona());
            $output->writeln("<info>Tools:</info>");
            foreach ($agent->getTools() as $tool) {
                $output->writeln("- $tool");
            }
        }

        return Command::SUCCESS;
    }
}
