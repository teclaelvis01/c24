<?php

declare(strict_types=1);

namespace App\FinancialProducts\Infrastructure\Command;

use App\FinancialProducts\Application\Service\CreditCardImportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-credit-cards',
    description: 'Import credit cards from the external API'
)]
class ImportCreditCardsCommand extends Command
{
    public function __construct(
        private readonly CreditCardImportService $importService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Importing credit cards from external API');

        try {
            $this->importService->importCreditCards();
            $io->success('Credit cards imported successfully');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error importing credit cards: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 