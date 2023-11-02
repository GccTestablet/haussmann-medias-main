<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\Work\Work;
use App\Form\Dto\Work\WorkReversionFormDto;
use App\Service\Work\WorkReversionManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class WorkReversionFormDtoFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkReversionManager $workReversionManager,
    ) {}

    public function create(Work $work): WorkReversionFormDto
    {
        $dto = new WorkReversionFormDto($work);

        foreach ($work->getBroadcastChannels() as $channel) {
            $workReversion = $work->getWorkReversion($channel);
            $dto->addReversion(
                WorkReversionFormDto::getFormName($channel),
                $workReversion?->getPercentageReversion()
            );
        }

        return $dto;
    }

    public function updateEntity(Work $work, WorkReversionFormDto $dto): void
    {
        $workReversions = new ArrayCollection();
        foreach ($work->getBroadcastChannels() as $channel) {
            $value = $dto->getReversion(WorkReversionFormDto::getFormName($channel));
            $workReversion = $this->workReversionManager->find($work, $channel);
            if (!$value && $workReversion) {
                $this->entityManager->remove($workReversion);
            }

            if (!$value) {
                continue;
            }

            $workReversion = $this->workReversionManager->findOrCreate($work, $channel);

            $workReversion->setPercentageReversion($value);
            $workReversions->add($workReversion);
        }

        $work->setWorkReversions($workReversions);
    }
}
