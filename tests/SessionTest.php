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

use RuntimeException;
use LovePHPForever\Core\Session;
use PHPUnit\Framework\TestCase;

/**
 * Test the session manager.
 */
class SessionTest extends TestCase
{
    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testSessionConstruction(): void
    {
        $session = new Session('session');
        $this->assertEquals(\session_name(), 'session');
        $this->assertTrue($session->exists());
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testSessionPutMethod(): void
    {
        $session = new Session('session');
        $this->assertEquals(\session_name(), 'session');
        $this->assertTrue($session->exists());
        $this->assertTrue(!$session->has('hello'));
        $session->put('hello', 'World');
        $this->assertTrue($session->has('hello'));
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testSessionFlashMethod(): void
    {
        $session = new Session('session');
        $this->assertEquals(\session_name(), 'session');
        $this->assertTrue($session->exists());
        $this->assertTrue(!$session->has('hello'));
        $session->put('hello', 'World');
        $this->assertTrue($session->has('hello'));
        $flashed = $session->flash('hello');
        $this->assertTrue(!$session->has('hello'));
        $this->assertTrue($session->get('hello', \true));
        $this->assertEquals($flashed, 'World');
    }

    /**
     * @runInSeparateProcess
     *
     * @return void Returns nothing.
     */
    public function testSessionDestroyMethod(): void
    {
        $session = new Session('session');
        $this->assertEquals(\session_name(), 'session');
        $this->assertTrue($session->exists());
        $this->assertTrue(!$session->has('hello'));
        $session->put('hello', 'World');
        $this->assertTrue($session->has('hello'));
        $flashed = $session->flash('hello');
        $this->assertTrue(!$session->has('hello'));
        $this->assertTrue($session->get('hello', \true));
        $this->assertEquals($flashed, 'World');
        $session->stop();
        $this->assertTrue(!$session->exists());
    }
}
