<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        'no_trailing_whitespace' => false,
        'new_with_braces' => ['anonymous_class' => false],
        'method_argument_space' => ['on_multiline' => 'ignore'],
        'lambda_not_used_import' => false,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => false,
        'phpdoc_to_comment' => false,
        'blank_line_before_statement' => ['statements' => [
            'continue',
            'declare',
            'exit',
            'goto',
            'include',
            'include_once',
            'phpdoc',
            'require',
            'require_once',
            'return',
            'switch',
            'throw',
            'try',
            'yield',
            'yield_from',
        ]],
    ])
    ->setFinder($finder)
;
