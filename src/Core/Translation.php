<?php declare(strict_types=1);
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

namespace LovePHPForever\Core;

use RuntimeException;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;

/**
 * The translation handler.
 */
final class Translation implements Translator
{
    /**
     * Construct a new translation handler.
     *
     * @param string $currLang The currently set language.
     *
     * @return void Returns nothing.
     */
    public function __construct(string $currLang = 'en-US')
    {
        $this->translator = new Translator($currLang);
        $this->translator->addLoader('array', new ArrayLoader());
    }

    /**
     * Translate a language variable.
     *
     * @param string $resourcePath The filename of the array resource.
     *
     * @return void Returns nothing.
     */
    pulic function add(string $resource): void
    {
        $resourceData = require __DIR__ . "/../../translations/{$resource}.php";
        $translator->addResource('array', $resourceData, $resource);
    }
 
    /**
     * Translate a language variable.
     *
     * @param string $langVar The translation key.
     *
     * @return string Returns the translation.
     */
    public function trans(string $langVar): string
    {
        return $this->translator->trans($langVar);
    }
}
