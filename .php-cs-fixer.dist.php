<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        'phpdoc_param_order' => true,
        'cast_spaces' => ['space' => 'none'],
        'trailing_comma_in_multiline' => true,
        'blank_line_after_opening_tag' => false,
        'array_indentation' => true,
        'void_return' => true,
        'trim_array_spaces' => true,
        'yoda_style' => ['identical' => false],
        'phpdoc_add_missing_param_annotation' => true,
        'no_empty_comment' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
            'allow_unused_params' => false,
            'allow_hidden_params' => false,
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'new_line_for_chained_calls'
        ],
        'phpdoc_order_by_value' => [
            'annotations' => [
                "covers",
                "dataProvider",
                "author",
                "internal",
                "property",
                "throws",
                "uses"
            ]
        ],
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => [
                'class',
                'const',
                'function',
            ],
        ],
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'none',
                'method' => 'one',
                'property' => 'none',
                'trait_import' => 'one',
            ],
        ],
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'return',
                'throw',
                'try',
            ],
        ],
    ]);
