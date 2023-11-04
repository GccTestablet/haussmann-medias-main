<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Shared;

use App\Tests\Traits\NormalizerTrait;
use App\Tools\Parser\ArrayParser;
use App\Tools\Parser\ObjectParser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

abstract class AbstractFixture extends Fixture implements ContainerAwareInterface
{
    use NormalizerTrait;

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
     * @param array<string> $excludeFields
     *
     * @return object|array<string|int, mixed>
     */
    protected function merge(object|array $source, object|array $target, array $excludeFields = []): object|array
    {
        foreach ($excludeFields as $field) {
            unset($source[$field]);
        }

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

    //    /**
    //     * @param array<string, mixed> $data
    //     * @param array<string> $fields
    //     */
    //    protected function denormalizeDateTimeFields(array &$data, array $fields): void
    //    {
    //        foreach ($fields as $field) {
    //            if (isset($data[$field])) {
    //                $data[$field] = new \DateTime($data[$field]);
    //            }
    //        }
    //    }

    //    /**
    //     * @param array<string, mixed> $data
    //     * @param array<string> $fields
    //     */
    //    protected function denormalizeReferenceFields(array &$data, array $fields): void
    //    {
    //        foreach ($fields as $field) {
    //            if (isset($data[$field])) {
    //                if (\is_array($data[$field])) {
    //                    foreach ($data[$field] as $key => $datum) {
    //                        $data[$field][$key] = $this->getReference($datum);
    //                    }
    //                } else {
    //                    $data[$field] = $this->getReference($data[$field]);
    //                }
    //            }
    //        }
    //    }

    //    /**
    //     * @param array<string, mixed> $data
    //     * @param array<string> $fields
    //     */
    //    protected function denormalizeArrayCollectionReferenceFields(array &$data, array $fields): void
    //    {
    //        foreach ($fields as $field) {
    //            if (isset($data[$field]) && \is_array($data[$field])) {
    //                foreach ($data[$field] as $key => $datum) {
    //                    $data[$field][$key] = $this->getReference($datum);
    //                }
    //
    //                $data[$field] = new ArrayCollection($data[$field]);
    //            }
    //        }
    //    }
}
