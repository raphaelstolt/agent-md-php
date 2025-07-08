<?php

declare(strict_types=1);

namespace Stolt\AgentMd\Commands;

use Stolt\AgentMd\AgentMd;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ValidateCommand extends Command
{
    protected static $defaultName = 'validate';

    protected function configure(): void
    {
        $this->setName(ValidateCommand::$defaultName)->setDescription('Validate the AGENT.md file')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to AGENT.md file')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format: text or json', 'text');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $format = $input->getOption('format');
        $path = $input->getArgument('file');

        try {
            $agent = new AgentMd($path);
            $errors = $agent->validate();
        } catch (\Throwable $e) {
            return $this->respond($output, $format, ['error' => $e->getMessage()], false);
        }

        if (empty($errors)) {
            return $this->respond($output, $format, ['status' => 'valid'], true);
        }

        return $this->respond($output, $format, ['errors' => $errors], false);
    }

    private function respond(OutputInterface $output, string $format, array $data, bool $success): int
    {
        if ($format === 'json') {
            $output->writeln(\json_encode($data, JSON_PRETTY_PRINT));
        } else {
            if ($success) {
                $output->writeln('<info>✅ AGENT.md is valid.</info>');
            } else {
                foreach (($data['errors'] ?? [$data['error']]) as $message) {
                    $output->writeln("<error>❌ $message</error>");
                }
            }
        }

        return $success ? Command::SUCCESS : Command::FAILURE;
    }
}
