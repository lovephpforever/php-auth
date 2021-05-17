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

use LovePHPForever\Core\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Test the validator.
 */
class ValidatorTest extends TestCase
{
    /**
     * @return void Returns nothing.
     */
    public function testBasicValidators(): void
    {
        $validator = new Validator();
        $violations = $validator->validate('Bernhard', [
            new Assert\NotBlank(),
        ]);
        $this->assertTrue(0 === \count($violations));
        $violations = $validator->validate('', [
            new Assert\NotBlank(),
        ]);
        $this->assertTrue(0 !== \count($violations));
        $violations = $validator->validate('Bernhard', [
            new Assert\Blank(),
        ]);
        $this->assertTrue(0 !== \count($violations));
        $violations = $validator->validate('', [
            new Assert\Blank(),
        ]);
        $this->assertTrue(0 === \count($violations));
        $violations = $validator->validate('Bernhard', [
            new Assert\NotNull(),
        ]);
        $this->assertTrue(0 === \count($violations));
        $violations = $validator->validate(null, [
            new Assert\NotNull(),
        ]);
        $this->assertTrue(0 !== \count($violations));
        $violations = $validator->validate(true, [
            new Assert\IsTrue(),
        ]);
        $this->assertTrue(0 === \count($violations));
        $violations = $validator->validate(false, [
            new Assert\IsTrue(),
        ]);
        $this->assertTrue(0 !== \count($violations));
        $violations = $validator->validate(false, [
            new Assert\IsFalse(),
        ]);
        $this->assertTrue(0 === \count($violations));
        $violations = $validator->validate(true, [
            new Assert\IsFalse(),
        ]);
        $this->assertTrue(0 !== \count($violations));
        $violations = $validator->validate(21, [
            new Assert\Type([
                'type' => 'integer',
            ]),
        ]);
        $this->assertTrue(0 === \count($violations));
        $violations = $validator->validate(true, [
            new Assert\Type([
                'type' => 'integer',
            ]),
        ]);
        $this->assertTrue(0 !== \count($violations));
    }
}
