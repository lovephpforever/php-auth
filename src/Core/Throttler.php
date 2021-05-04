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
use Symfony\Component\RateLimiter\RateLimiterFactory;

/**
 * The http-request throttler.
 */
final class Throttler
{
    /** @var array $limiter The rate-limiter factory. */
    private $limiter;

    /**
     * Construct a new http-request throttler.
     *
     * @param \Symfony\Component\RateLimiter\RateLimiterFactory $factory The rate-limiter factory.
     *
     * @return void Returns nothing.
     */
    public function __construct(RateLimiterFactory $factory)
    {
        $this->limiter = $factory->create($_SERVER['REMOTE_ADDR']);
    }

    /**
     * Throttle an http-request.
     *
     * @param string $namespace The throttle namespace.
     *
     * @return void Returns nothing.
     */
    public function throttle(string $namespace): void
    {
        if (\false === $this->limiter->consume(1)->isAccepted()) {
            throw new RuntimeException('Too many attempts made.');
        }
    }
}
