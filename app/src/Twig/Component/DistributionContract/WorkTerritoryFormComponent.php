<?php

declare(strict_types=1);

namespace App\Twig\Component\DistributionContract;

use App\Entity\Contract\DistributionContractWork;
use App\Form\DtoFactory\Contract\DistributionContractWorkTerritoryCollectionFormDtoFactory;
use App\Form\Handler\Contract\DistributionContractWorkTerritoryFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent(name: 'distribution_contract_work_territory_form', template: 'component/distribution_contract/work_territory_form.html.twig')]
class WorkTerritoryFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use LiveCollectionTrait;

    #[LiveProp(fieldName: 'formData')]
    public ?DistributionContractWork $contractWork;

    public function __construct(
        private readonly DistributionContractWorkTerritoryFormHandler $formHandler,
        private readonly DistributionContractWorkTerritoryCollectionFormDtoFactory $formDtoFactory,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        $dto = $this->formDtoFactory->create($this->contractWork);

        return $this->formHandler->create($dto);
    }
}
