<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('vendor')
    ->notPath('bootstrap')
    ->notPath('storage')
    ->notPath('node_modules')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'length'],
        'single_quote' => true,
        'phpdoc_summary' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'no_unused_imports' => true,
        'no_extra_blank_lines' => true,
        'no_trailing_comma_in_list_call' => false,
        'no_whitespace_in_blank_line' => true,
        'no_whitespace_before_comma_in_array' => true,
        'trailing_comma_in_multiline' => true,
        'function_typehint_space' => true,
        'phpdoc_order' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'alpha',
        ],
        'phpdoc_to_comment' => true,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_summary' => true,
        'phpdoc_trim' => true,
        'phpdoc_single_line_var_spacing' => true,
        'not_operator_with_successor_space' => true,
    ])
    ->setFinder($finder);
