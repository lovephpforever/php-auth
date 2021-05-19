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

namespace LovePHPForever\Framework\Tests;

use LovePHPForever\Core\Translation;
use PHPUnit\Framework\TestCase;
use RangeException;

/**
 * Test the translation class.
 */
class TranslationTest extends TestCase
{
    /**
     * @return void Returns nothing.
     */
    public function testTranslatorConstruction(): void
    {
        $translator = new Translation();
        $this->assertEquals($translator->trans('Hello World!'), 'Hello World!');
        $this->assertEquals($translator->trans('Hello Tom!'), 'Hello Tom!');
        $this->assertEquals($translator->trans('Hello Mark!'), 'Hello Mark!');
        $this->assertEquals($translator->trans('Hello Henry!'), 'Hello Henry!');
    }

    /**
     * @return void Returns nothing.
     */
    public function testTranslatorResourceLang(): void
    {
        $translator = new Translation('fr_FR');
        $translator->add(__DIR__ . "/../bin/fr_FR.php", 'fr_FR');
        $this->assertEquals($translator->trans('Hello World!'), 'Bonjour le monde!');
        $this->assertEquals($translator->trans('Hello Tom!'), 'Allô Tom!');
        $this->assertEquals($translator->trans('Hello Mark!'), 'Bonjour Mark!');
        $this->assertEquals($translator->trans('Hello Henry!'), 'Bonjour Henry!');
    }

    /**
     * @return void Returns nothing.
     */
    public function testTranslatorDefLang(): void
    {
        $translator = new Translation();
        $translator->add(__DIR__ . "/../bin/fr_FR.php", 'fr_FR');
        $this->assertEquals($translator->trans('Hello World!'), 'Hello World!');
        $this->assertEquals($translator->trans('Hello Tom!'), 'Hello Tom!');
        $this->assertEquals($translator->trans('Hello Mark!'), 'Hello Mark!');
        $this->assertEquals($translator->trans('Hello Henry!'), 'Hello Henry!');
    }

    /**
     * @return void Returns nothing.
     */
    public function testTranslatorDefLangForcedLocal(): void
    {
        $translator = new Translation('fr_FR');
        $translator->add(__DIR__ . "/../bin/fr_FR.php", 'fr_FR');
        $this->assertEquals($translator->trans('Hello World!', [], null, 'en-US'), 'Hello World!');
        $this->assertEquals($translator->trans('Hello Tom!', [], null, 'en-US'), 'Hello Tom!');
        $this->assertEquals($translator->trans('Hello Mark!', [], null, 'en-US'), 'Hello Mark!');
        $this->assertEquals($translator->trans('Hello Henry!', [], null, 'en-US'), 'Hello Henry!');
    }
}
