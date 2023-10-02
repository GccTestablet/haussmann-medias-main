<?php

use PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer;
use PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer;
use PhpCsFixerCustomFixers\Fixer\NoDoctrineMigrationsGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoImportFromGlobalNamespaceFixer;

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src/',
        __DIR__ . '/tests/',
    ])
;

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP81Migration' => true,
        '@PHP80Migration:risky' => true,
        '@PHPUnit84Migration:risky' => true,
        'no_homoglyph_names' => true, // We don't generally use these, so it should be safe
        'phpdoc_align' => false, // We don't want to align these
        'phpdoc_summary' => false,  // This adds silly full-stops
        'no_empty_comment' => false, // This removes some of our comments which we use as separators
        'no_superfluous_phpdoc_tags' => false, // Makes too many changes, requires discussion
        'standardize_increment' => true, // Force increment style to be consistent
        'increment_style' => [
            'style' => 'pre', // force ++$i; increment style
        ],
        'single_quote' => false, // Needed but there are some places, eg. SQL strings, where this seems wrong to apply
        'single_line_throw' => false, // Makes too many changes, requires discussion
        'no_unreachable_default_argument_value' => false, // This one should be turned on, but needs auditing first
        'yoda_style' => false, // Removed for introduction of v2 to keep changes small
        'no_break_comment' => false, // Removed for introduction of v2 to keep changes small
        'native_function_invocation' => ['include' => ['@all']],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'new_line_for_chained_calls',
        ],
        'method_argument_space' => [
            'on_multiline' => 'ignore'
        ],
        (new MultilinePromotedPropertiesFixer)->getName() => true,
        (new ConstructorEmptyBracesFixer)->getName() => true,
        (new NoDoctrineMigrationsGeneratedCommentFixer())->getName() => true,
        (new NoImportFromGlobalNamespaceFixer())->getName() => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ;
