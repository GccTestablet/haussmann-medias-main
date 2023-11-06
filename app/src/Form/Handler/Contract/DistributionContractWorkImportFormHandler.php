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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DistributionContractWorkImportFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
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

        $contract = $dto->getDistributionContract();
        try {
            $this->contractWorkRevenueImporter->build(['contract' => $contract]);
            $records = $this->contractWorkRevenueImporter->getRecords($dto->getFile());
        } catch (\Exception $exception) {
            $form->addError(new FormError($exception->getMessage()));

            return parent::onFormNotSubmitAndValid($request, $form, $options);
        }

        foreach ($records as $record) {
            $work = $this->workManager->findOneByInternalId($record->getInternalId());
            if (!$work) {
                $form->addError(new FormError(\sprintf('Work with internal id "%s" does not exist', $record->getInternalId())));

                continue;
            }

            $contractWork = $this->distributionContractWorkManager->findOneByDistributionContractAndWork(
                $contract,
                $work
            );

            if (!$contractWork) {
                $form->addError(new FormError(\sprintf('Work "%s" not found in contract %s', $work->getName(), $contract->getName())));

                continue;
            }

            foreach ($record->getChannels() as $slug => $revenue) {
                $channel = $this->broadcastChannelManager->findOneBySlug($slug);
                if (!$channel) {
                    $form->addError(new FormError(\sprintf('Broadcast channel with slug "%s" does not exist', $slug)));

                    continue;
                }

                $workBroadcastChannels = $contract->getContractWork($work)?->getBroadcastChannels();
                if (!$workBroadcastChannels->contains($channel)) {
                    continue;
                }

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

        if (\count($form->getErrors()) > 0) {
            return parent::onFormNotSubmitAndValid($request, $form, $options);
        }

        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
