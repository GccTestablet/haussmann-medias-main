<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Contract\DistributionContract;
use App\Service\Setting\BroadcastChannelManager;
use App\Service\Work\WorkManager;
use App\Tools\Parser\CsvParser;

class DistributionContractTemplateGenerator
{
    /**
     * @var string[]
     */
    private array $headers = [
        'Internal Id',
        'Name',
    ];

    /**
     * @var array<string, string>
     */
    private array $rows = [];

    public function __construct(
        private readonly CsvParser $csvParser,
        private readonly BroadcastChannelManager $broadcastChannelManager,
        private readonly WorkManager $workManager,
    ) {}

    public function generate(DistributionContract $contract, string $fileName): void
    {
        $this->addHeaders();
        $this->addRows($contract);

        $writer = $this->csvParser->write($fileName);
        $writer->insertOne($this->headers);
        $writer->insertAll($this->rows);
    }

    private function addHeaders(): void
    {
        foreach ($this->broadcastChannelManager->findAll() as $channel) {
            $this->headers[] = $channel->getName();
        }
    }

    private function addRows(DistributionContract $contract): void
    {
        foreach ($this->workManager->findByDistributionContract($contract) as $work) {
            $this->rows[] = [
                'Internal Id' => $work->getInternalId(),
                'Name' => $work->getName(),
            ];
        }
    }
}
