<?php

declare(strict_types=1);

namespace App\Form\Handler\Contract;

use App\Entity\Contract\DistributionContractWorkRevenue;
use App\Form\Dto\Contract\DistributionContractWorkRevenueImportFormDto;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Contract\DistributionContractWorkRevenueImportFormType;
use App\Service\Contract\DistributionContractWorkManager;
use App\Service\Contract\DistributionContractWorkRevenueImporter;
use App\Service\Setting\BroadcastChannelManager;
use App\Service\Work\WorkManager;
use App\Tools\Parser\StringParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DistributionContractWorkImportFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StringParser $stringParser,
        private readonly DistributionContractWorkRevenueImporter $contractWorkRevenueImporter,
        private readonly DistributionContractWorkManager $distributionContractWorkManager,
        private readonly WorkManager $workManager,
        private readonly BroadcastChannelManager $broadcastChannelManager
    ) {}

    protected static string $form = DistributionContractWorkRevenueImportFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof DistributionContractWorkRevenueImportFormDto) {
            throw new UnexpectedTypeException($dto, DistributionContractWorkRevenueImportFormDto::class);
        }

        $this->contractWorkRevenueImporter->build(['contract' => $dto->getDistributionContract()]);
        $records = $this->contractWorkRevenueImporter->getRecords($dto->getFile());

        foreach ($records as $record) {
            $work = $this->workManager->findOneByInternalId($record->getInternalId());
            $contractWork = $this->distributionContractWorkManager->findOneByDistributionContractAndWork(
                $dto->getDistributionContract(),
                $work
            );

            foreach ($record->getChannels() as $channelName => $revenue) {
                $slug = $this->stringParser->slugify($channelName);
                $channel = $this->broadcastChannelManager->findOneBySlug($slug);

                $revenue = (new DistributionContractWorkRevenue())
                    ->setContractWork($contractWork)
                    ->setStartsAt($dto->getStartsAt())
                    ->setEndsAt($dto->getEndsAt())
                    ->setBroadcastChannel($channel)
                    ->setAmount($revenue)
                    ->setCurrency($dto->getCurrency())
                ;

                $this->entityManager->persist($revenue);
            }
        }

        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
