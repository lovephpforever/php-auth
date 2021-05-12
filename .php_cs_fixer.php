<?php

declare(strict_types=1);
/**
 * MIT License
 * 
 * Copyright (c) 2021 LovePHPForever
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/test');

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['align_double_arrow' => false, 'align_equals' => false],
        'blank_line_after_opening_tag' => true,
        'blank_line_before_return' => true,
        'cast_spaces' => true,
        'concat_space' => ['spacing' => 'none'],
        'declare_strict_types' => true,
        'method_separation' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_spaces_around_offset' => ['positions' => ['inside', 'outside']],
        'no_unneeded_control_parentheses' => true,
        'no_unused_imports' => true,
        'phpdoc_align' => true,
        'phpdoc_no_access' => true,
        'phpdoc_separation' => true,
        'pre_increment' => true,
        'single_quote' => true,
        'trim_array_spaces' => true,
        'single_blank_line_before_namespace' => true
    ])
    ->setFinder($finder);

return $config;
