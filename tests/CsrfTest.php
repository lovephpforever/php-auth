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

use InvalidArgumentException;
use LovePHPForever\Core\Csrf;
use LovePHPForever\Core\Utilities;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Session\Session;
use UnderflowException;

/**
 * Test the validator.
 */
class CsrfTest extends TestCase
{
    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testExceptionOnNonRunningSession(): void
    {
        $this->expectException(RuntimeException::class);
        $session = new Session();
        $helper = new Utilities();
        $protector = new Csrf($session, $helper);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testCsrfTokenGeneration(): void
    {
        $session = new Session();
        $session->start();
        $helper = new Utilities();
        $protector = new Csrf($session, $helper);
        $this->assertTrue(\is_string($protector->generate()));
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testCsrfTokenInitialization(): void
    {
        $session = new Session();
        $session->start();
        $helper = new Utilities();
        $protector = new Csrf($session, $helper);
        $protector->initialize();
        $this->assertTrue($session->has('token'));
        $this->assertTrue($session->has('accessed_from'));
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testCsrfTokenVerificationExceptionOnMissingToken(): void
    {
        $this->expectException(UnderflowException::class);
        $session = new Session();
        $session->start();
        $helper = new Utilities();
        $protector = new Csrf($session, $helper);
        $protector->initialize();
        $protector->verify([]);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testCsrfTokenVerificationExceptionOnNotSetToken(): void
    {
        $this->expectException(UnderflowException::class);
        $session = new Session();
        $session->start();
        $helper = new Utilities();
        $protector = new Csrf($session, $helper);
        $protector->verify(['token' => 'wrong_token']);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testCsrfTokenVerificationExceptionOnWrongToken(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $session = new Session();
        $session->start();
        $helper = new Utilities();
        $protector = new Csrf($session, $helper);
        $protector->initialize();
        $protector->verify(['token' => 'wrong_token']);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testCsrfTokenVerificationExceptionOnWrongAccessLocation(): void
    {
        $this->expectException(RuntimeException::class);
        $session = new Session();
        $session->start();
        $helper = new Utilities();
        $protector = new Csrf($session, $helper);
        $protector->initialize();
        $session->set('accessed_from', 'wrong_location');
        $protector->verify(['token' => $session->get('token')]);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testCsrfTokenIsValid(): void
    {
        $session = new Session();
        $session->start();
        $helper = new Utilities();
        $protector = new Csrf($session, $helper);
        $protector->initialize();
        $this->assertTrue($session->has('token'));
        $this->assertTrue($session->has('accessed_from'));
        $protector->verify(['token' => $session->get('token')]);
        $this->assertTrue(\true);
    }
}
