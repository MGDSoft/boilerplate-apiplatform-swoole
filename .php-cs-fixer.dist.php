<?php

$finder = (new PhpCsFixer\Finder())
    ->exclude(['var'])
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@DoctrineAnnotation'    => true,
        '@PHP71Migration'        => true,
        '@PSR1'                  => true,
        '@PSR2'                  => true,
        '@Symfony'               => true,
        'list_syntax'            => ['syntax' => 'long'],
        'array_syntax'           => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'operators' => [
                '='  => 'single_space',
                '=>' => 'align',
            ],
        ],
        'align_multiline_comment' => [
            'comment_type' => 'all_multiline',
        ],
    ])
    ->setFinder($finder)
;
