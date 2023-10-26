<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class CoreExtensionRuntime implements RuntimeExtensionInterface
{
    public function callStatic(string $class, string $method, mixed ...$args): mixed
    {
        if (!\class_exists($class)) {
            throw new \Exception(\sprintf('Class "%s" does not exists', $class));
        }

        if (!\method_exists($class, $method)) {
            throw new \Exception(\sprintf('Method "%s" does not exists in class "%s"', $method, $class));
        }

        return \forward_static_call_array([$class, $method], $args);
    }

    /**
     * @param array<mixed> $arguments
     */
    public function executeClosure(\Closure $closure, array $arguments = []): mixed
    {
        return $closure(...$arguments);
    }

    public function isInstanceOf(mixed $var, string $instance): bool
    {
        return $var instanceof $instance;
    }
}
