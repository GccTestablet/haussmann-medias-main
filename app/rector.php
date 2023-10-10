<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Symfony61\Rector\Class_\MagicClosureTwigExtensionToNativeMethodsRector;

return static function (RectorConfig $rectorConfig): void
{
    $kernelDevDebugContainer = static function(): ?string {
        $envs = [
            'test',
            'dev',
        ];

        foreach ($envs as $env) {
            $fileName = __DIR__ . '/var/cache/'.$env.'/App_KernelDevDebugContainer.xml';
            if (file_exists($fileName)) {
                return $fileName;
            }
        }

        return null;
    };

    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    if ($kernelDevDebugContainer()) {
        $rectorConfig->symfonyContainerXml($kernelDevDebugContainer());
    }

    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);

    $rectorConfig->skip([
        MagicClosureTwigExtensionToNativeMethodsRector::class,
    ]);
    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        SymfonySetList::SYMFONY_60,
        SymfonySetList::SYMFONY_61,
        SymfonySetList::SYMFONY_62,
        SymfonySetList::SYMFONY_63,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_90,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::GEDMO_ANNOTATIONS_TO_ATTRIBUTES,
    ]);

    $rectorConfig->ruleWithConfiguration(StringClassNameToClassConstantRector::class, [
        'Description',
        'Reference'
    ]);
};
