<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Shared;

use App\Tools\Parser\ArrayParser;
use App\Tools\Parser\ObjectParser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

abstract class AbstractFixture extends Fixture implements ContainerAwareInterface
{
    private ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    protected function getService(string $id): mixed
    {
        return $this->container->get($id);
    }

    /**
     * @param object|array<string|int, mixed> $source
     * @param object|array<string|int, mixed> $target
     *
     * @return object|array<string|int, mixed>
     */
    protected function merge(object|array $source, object|array $target): object|array
    {
        if (\is_array($source) && \is_array($target)) {
            /** @var ArrayParser $arrayParser */
            $arrayParser = $this->getService(ArrayParser::class);

            return $arrayParser->merge($source, $target);
        }

        if (\is_array($source) && \is_object($target)) {
            /** @var ObjectParser $objectParser */
            $objectParser = $this->getService(ObjectParser::class);

            return $objectParser->mergeFromArray($source, $target);
        }

        throw new \LogicException('You should never be here!');
    }
}
