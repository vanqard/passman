<?php

$finder = (new PhpCsFixer\Finder())
    ->in([__DIR__ . '/PassMan', __DIR__ . '/test']);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
        'ordered_imports' => true,
        'no_unused_imports' => true,
    ])
    ->setFinder($finder);
