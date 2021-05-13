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
use LovePHPForever\Core\PasswordHasher;
use PHPUnit\Framework\TestCase;

/**
 * Test the password hasher.
 */
class PasswordHasherTest extends TestCase
{
    /**
     * @return void Returns nothing.
     */
    public function testBcryptConstruction(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_BCRYPT);
        $this->assertEquals(\PASSWORD_BCRYPT, $hasher->passwordAlgo);
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2iConstruction(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2I);
        $this->assertEquals(\PASSWORD_ARGON2I, $hasher->passwordAlgo);
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2idConstruction(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2ID);
        $this->assertEquals(\PASSWORD_ARGON2ID, $hasher->passwordAlgo);
    }

    /**
     * @return void Returns nothing.
     */
    public function testBcryptException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $hasher = new PasswordHasher(\PASSWORD_BCRYPT);
        $this->assertEquals(\PASSWORD_BCRYPT, $hasher->passwordAlgo);
        $password = $hasher->compute('eP&=Mj]Vb],h$=Z@]LaK{b%V@M`(HY6"$Nd5UB]YjC^.BSuZM~J~LpY9nt\'8"Y{^bSA3{PqL(');
    }

    /**
     * @return void Returns nothing.
     */
    public function testBcryptCompute(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_BCRYPT);
        $this->assertEquals(\PASSWORD_BCRYPT, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2iCompute(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2I);
        $this->assertEquals(\PASSWORD_ARGON2I, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2idCompute(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2ID);
        $this->assertEquals(\PASSWORD_ARGON2ID, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testBcryptVerify(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_BCRYPT);
        $this->assertEquals(\PASSWORD_BCRYPT, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $this->assertTrue(!$hasher->verify('incorrect', $password));
        $this->assertTrue($hasher->verify('password', $password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2iVerify(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2I);
        $this->assertEquals(\PASSWORD_ARGON2I, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $this->assertTrue(!$hasher->verify('incorrect', $password));
        $this->assertTrue($hasher->verify('password', $password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2idVerify(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2ID);
        $this->assertEquals(\PASSWORD_ARGON2ID, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $this->assertTrue(!$hasher->verify('incorrect', $password));
        $this->assertTrue($hasher->verify('password', $password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testBcrypt2Argon2iRehash(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_BCRYPT);
        $this->assertEquals(\PASSWORD_BCRYPT, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $hasher2 = new PasswordHasher(\PASSWORD_ARGON2I);
        $this->assertEquals(\PASSWORD_ARGON2I, $hashe2->passwordAlgo);
        $this->assertTrue($hashe2->needsRehash($password));
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $this->assertTrue(!$hashe2->needsRehash($password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testBcrypt2Argon2idRehash(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_BCRYPT);
        $this->assertEquals(\PASSWORD_BCRYPT, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $hasher2 = new PasswordHasher(\PASSWORD_ARGON2ID);
        $this->assertEquals(\PASSWORD_ARGON2ID, $hashe2->passwordAlgo);
        $this->assertTrue($hashe2->needsRehash($password));
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $this->assertTrue(!$hashe2->needsRehash($password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2i2BcryptRehash(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2I);
        $this->assertEquals(\PASSWORD_ARGON2I, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $hasher2 = new PasswordHasher(\PASSWORD_BCRYPT);
        $this->assertEquals(\PASSWORD_BCRYPT, $hashe2->passwordAlgo);
        $this->assertTrue($hashe2->needsRehash($password));
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $this->assertTrue(!$hashe2->needsRehash($password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2i2Argon2idRehash(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2I);
        $this->assertEquals(\PASSWORD_ARGON2I, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $hasher2 = new PasswordHasher(\PASSWORD_ARGON2ID);
        $this->assertEquals(\PASSWORD_ARGON2ID, $hashe2->passwordAlgo);
        $this->assertTrue($hashe2->needsRehash($password));
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $this->assertTrue(!$hashe2->needsRehash($password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2id2BcryptRehash(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2ID);
        $this->assertEquals(\PASSWORD_ARGON2ID, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $hasher2 = new PasswordHasher(\PASSWORD_BCRYPT);
        $this->assertEquals(\PASSWORD_BCRYPT, $hashe2->passwordAlgo);
        $this->assertTrue($hashe2->needsRehash($password));
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $this->assertTrue(!$hashe2->needsRehash($password));
    }

    /**
     * @return void Returns nothing.
     */
    public function testArgon2id2Argon2iRehash(): void
    {
        $hasher = new PasswordHasher(\PASSWORD_ARGON2ID);
        $this->assertEquals(\PASSWORD_ARGON2ID, $hasher->passwordAlgo);
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $hasher2 = new PasswordHasher(\PASSWORD_ARGON2I);
        $this->assertEquals(\PASSWORD_ARGON2I, $hashe2->passwordAlgo);
        $this->assertTrue($hashe2->needsRehash($password));
        $password = $hasher->compute('password');
        $this->assertTrue(\is_string($password));
        $this->assertTrue(!$hashe2->needsRehash($password));
    }
}
