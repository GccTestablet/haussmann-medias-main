<?php

declare(strict_types=1);

namespace App\Twig\Component\Layout;

use App\Entity\Work;
use App\Model\Layout\SearchBarResult\SearchBarCategoryResult;
use App\Model\Layout\SearchBarResult\SearchBarResult;
use App\Service\Contract\ContractManager;
use App\Service\Work\WorkManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(name: 'layout_search_bar', template: 'component/layout/search_bar.html.twig')]
class SearchBar
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $query = null;

    public function __construct(
        private readonly WorkManager $workManager,
        private readonly ContractManager $contractManager
    ) {}

    public function getResults(): array
    {
        if (empty($this->query)) {
            return [];
        }

        $results = [];

        foreach ($this->workManager->findBySearchQuery($this->query) as $work) {
            $results['Work'][] = $work->getName();
        }

        $categoryResult = new SearchBarCategoryResult('Contract');
        foreach ($this->contractManager->findBySearchQuery($this->query) as $contract) {
            $categoryResult->addResult(new SearchBarResult($contract->getOriginalFileName()));
            $results['Contract'][] = $contract->getOriginalFileName();
        }

        return $results;
    }
}
