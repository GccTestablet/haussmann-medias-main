<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Contract\DistributionContract;
use App\Model\Importer\Contract\DistributionContractWorkRevenueImporterModel;
use App\Service\Work\WorkManager;
use App\Tools\Parser\CsvParser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DistributionContractWorkRevenueImporter
{
    private const INTERNAL_ID = 'ID';
    private const NAME = 'NAME';

    /**
     * @var string[]
     */
    private array $headers = [
        self::INTERNAL_ID,
        self::NAME,
    ];

    /**
     * @var array<string, string>
     */
    private array $rows = [];

    public function __construct(
        private readonly CsvParser $csvParser,
        private readonly WorkManager $workManager,
    ) {}

    /**
     * @param array<string, mixed> $options
     */
    public function build(array $options = []): void
    {
        $distributionContract = $options['contract'];
        if (!$distributionContract instanceof DistributionContract) {
            throw new UnexpectedTypeException($distributionContract, DistributionContract::class);
        }

        $this->addHeaders($distributionContract);
        $this->addRows($distributionContract);
    }

    /**
     * @return DistributionContractWorkRevenueImporterModel[]
     */
    public function getRecords(UploadedFile $file): array
    {
        $csvReader = $this->csvParser->read($file->getRealPath());

        $header = $csvReader->getHeader();
        if ($header !== $this->headers) {
            throw new \Exception(\sprintf(
                'Headers in file are different from template. Expected: "%s", got: "%s"',
                \implode(',', $this->headers),
                \implode(',', $header)
            ));
        }

        $records = [];
        foreach ($csvReader->getRecords() as $record) {
            $model = new DistributionContractWorkRevenueImporterModel($record[self::INTERNAL_ID], $record[self::NAME]);
            foreach ($this->headers as $header) {
                if (\in_array($header, [self::INTERNAL_ID, self::NAME], true)) {
                    continue;
                }

                if (\in_array($record[$header], ['', 'N.A.'], true)) {
                    continue;
                }

                $model->addChannel($header, $record[$header]);
            }

            $records[] = $model;
        }

        return $records;
    }

    public function generateTemplate(string $fileName): void
    {
        $csvWriter = $this->csvParser->write($fileName);
        $csvWriter->insertOne($this->headers);
        $csvWriter->insertAll($this->rows);
    }

    private function addHeaders(DistributionContract $contract): void
    {
        foreach ($contract->getBroadcastChannels() as $channel) {
            $this->headers[] = \strtoupper($channel->getSlug());
        }
    }

    private function addRows(DistributionContract $contract): void
    {
        foreach ($this->workManager->findByDistributionContract($contract) as $work) {
            $row = [
                self::INTERNAL_ID => $work->getInternalId(),
                self::NAME => $work->getName(),
            ];

            $workBroadcastChannels = $contract->getContractWork($work)?->getBroadcastChannels();
            foreach ($contract->getBroadcastChannels() as $channel) {
                $row[\strtoupper($channel->getSlug())] = !$workBroadcastChannels->contains($channel) ? 'N.A.' : null;
            }

            $this->rows[] = $row;
        }
    }
}
