<?php

declare(strict_types=1);

namespace App\Twig\Component\Layout;

use App\Entity\Work;
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
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function getResults(): array
    {
        \dump($this->query);

        if (empty($this->query)) {
            return [];
        }

        return $this->entityManager->getRepository(Work::class)->createQueryBuilder('w')
            ->where('w.name LIKE :query OR w.originalName LIKE :query OR w.internalId LIKE :query OR w.imdbId LIKE :query')
            ->setParameter('query', '%'.$this->query.'%')
            ->getQuery()
            ->getResult()
        ;

        //        $formattedPackages = [];
        //        foreach ($packages as $package) {
        //            $formattedPackages[] = [
        //                'name' => $package->getName(),
        //                'description' => $package->getDescription(),
        //            ];
        //        }
        //        return $formattedPackages;
    }
}
