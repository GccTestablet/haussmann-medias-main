<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use Doctrine\Common\Collections\ArrayCollection;

trait NormalizerTrait
{
    /**
     * @param array<string, mixed> $data
     * @param array<string> $fields
     */
    protected function denormalizeDateTimeFields(array &$data, array $fields): void
    {
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $data[$field] = new \DateTime($data[$field]);
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string> $fields
     */
    protected function denormalizeReferenceFields(array &$data, array $fields): void
    {
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                if (\is_array($data[$field])) {
                    foreach ($data[$field] as $key => $datum) {
                        $data[$field][$key] = $this->getReference($datum);
                    }
                } else {
                    $data[$field] = $this->getReference($data[$field]);
                }
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string> $fields
     */
    protected function denormalizeArrayCollectionReferenceFields(array &$data, array $fields): void
    {
        foreach ($fields as $field) {
            if (isset($data[$field]) && \is_array($data[$field])) {
                foreach ($data[$field] as $key => $datum) {
                    $data[$field][$key] = $this->getReference($datum);
                }

                $data[$field] = new ArrayCollection($data[$field]);
            }
        }
    }
}
