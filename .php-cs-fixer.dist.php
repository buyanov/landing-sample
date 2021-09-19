<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$year = date('Y');
$header = <<<TXT
Copyright © $year Buyanov Danila
Package: Landing
TXT;

$finder = Finder::create()
    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/app')
    ->exclude(__DIR__ . '/vendor')
;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12'                            => true,
        '@PHP80Migration:risky'             => true,
        'list_syntax'                       => ['syntax' => 'short'],
        'no_unused_imports'                 => true,
        'declare_strict_types'              => true,
        'void_return'                       => true,
        'ordered_class_elements'            => true,
        'linebreak_after_opening_tag'       => true,
        'single_quote'                      => true,
        'no_blank_lines_after_phpdoc'       => false,
        'unary_operator_spaces'             => true,
        'no_useless_else'                   => true,
        'no_useless_return'                 => true,
        'trailing_comma_in_multiline'       => true,
        'single_blank_line_before_namespace' => true,
        'blank_line_before_statement'        => true,
        'header_comment' => [
            'header' => $header,
            'location' => 'after_open',
            'separate' => 'bottom',
        ],
    ])
    ->setFinder($finder)
    ;
