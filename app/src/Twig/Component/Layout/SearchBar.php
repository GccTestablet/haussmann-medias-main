<?php

declare(strict_types=1);

namespace App\Twig\Component\Layout;

use App\Model\Layout\SearchBarCategoryResult;
use App\Model\Layout\SearchBarResult;
use App\Service\Contract\ContractManager;
use App\Service\Work\WorkManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatableMessage;
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
        private readonly ContractManager $contractManager,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {}

    public function getResults(): array
    {
        if (empty($this->query)) {
            return [];
        }

        $results = [];

        $paginator = $this->workManager->findBySearchQuery($this->query);
        $workResult = new SearchBarCategoryResult(new TranslatableMessage('Works',[], 'work'), $paginator->count());
        foreach ($paginator->getIterator() as $work) {
            $resultDTO = new SearchBarResult(
                $work->getOriginalName(),
                $this->urlGenerator->generate('app_work_show', ['id' => $work->getId()])
            );

            $workResult->addResult($resultDTO);
        }
        $results[] = $workResult;

        $paginator = $this->contractManager->findBySearchQuery($this->query);
        $categoryResult = new SearchBarCategoryResult(new TranslatableMessage('Acquisition contracts',[], 'contract'), $paginator->count());
        foreach ($paginator->getIterator() as $contract) {
            $resultDTO = new SearchBarResult(
                $contract->getOriginalFileName(),
                $this->urlGenerator->generate('app_acquisition_contract_show', ['id' => $contract->getId()])
            );

            $categoryResult->addResult($resultDTO);
        }
        $results[] = $categoryResult;

        return $results;
    }
}
