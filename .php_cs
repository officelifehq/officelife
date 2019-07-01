<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('vendor')
    ->notPath('bootstrap')
    ->notPath('storage')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sortAlgorithm' => 'length'],
        'single_quote' => true,
        'phpdoc_summary' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'no_unused_imports' => true,
        'no_trailing_comma_in_list_call' => false,
        'no_whitespace_in_blank_line' => true,
        'no_whitespace_before_comma_in_array' => true,
        'trailing_comma_in_multiline_array' => true,
        'function_typehint_space' => true,
    ])
    ->setFinder($finder);
