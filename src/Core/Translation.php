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
use Symfony\Component\Translation\Translator as SymfonyTranslator;
use Symfony\Component\Translation\Loader\ArrayLoader;

/**
 * The translation handler.
 */
final class Translation implements Translator
{
    /** @var \Symfony\Component\Translation\Translator $translator The symfony translator. */
    private $translator;

    /**
     * Construct a new translation handler.
     *
     * @param string $currLang The currently set language.
     *
     * @return void Returns nothing.
     */
    public function __construct(string $currLang = 'en-US')
    {
        $this->translator = new SymfonyTranslator($currLang);
        $this->translator->addLoader('array', new ArrayLoader());
    }

    /**
     * Translate a language variable.
     *
     * @param string $resourcePath The filename of the array resource.
     *
     * @psalm-suppress UnresolvableInclude
     *
     * @return void Returns nothing.
     */
    public function add(string $resource, string $resourceLang): void
    {
        /** @var array */
        $resourceData = require $resource;
        $this->translator->addResource('array', $resourceData, $resourceLang);
    }
 
    /**
     * Translate a language variable.
     *
     * @param string $langVar   The translation key.
     * @param array  $params    The text parameters.
     * @param string $domain    The message domain.
     * @param string $forceLang The local to enforce.
     *
     * @return string Returns the translation.
     */
    public function trans(string $langVar, array $params = [], string $domain = null, string $forceLang = null): string
    {
        return $this->translator->trans($langVar, $params, $domain, $forceLang);
    }
}
