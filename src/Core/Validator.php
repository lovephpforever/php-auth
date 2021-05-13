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

use Symfony\Component\Validator\Validation;

/**
 * The class validator.
 */
final class Validator
{
    /** @var Symfony\Component\Validator\Validator\ValidatorInterface $validator The class validator. */
    private $validator;

    /**
     * Construct a new class validator.
     *
     * @return void Returns nothing.
     */
    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * Validate the value against the constraints.
     *
     * @param mixed $value       The value to check.
     * @param array $constraints The validation constraints.
     *
     * @return mixed Returns the violations.
     */
    public function validate(mixed $value, array $constraints): string
    {
        return $this->validator->validate($value, $constraints);
    }
}
