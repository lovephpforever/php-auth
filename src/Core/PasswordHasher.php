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

use InvalidArgumentException;
use ParagonIE\ConstantTime\Binary;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The password hasher.
 */
final class PasswordHasher
{
    /** @var array $options The password hasher options. */
    private $options = [];

    /**
     * Construct a new password hasher.
     *
     * @param int|string $passwordAlgo The password hasher algorithm to use.
     * @param array      $options      The password hasher options.
     *
     * @return void Returns nothing.
     */
    public function __construct(public int|string $passwordAlgo, array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * Compute a new hash.
     *
     * @param string $password The password to hash.
     *
     * @return null|string Returns the hashed password.
     */
    public function compute(string $password): ?string
    {
        if ($this->passwordAlgo === \PASSWORD_BCRYPT && Binary::safeStrlen($password) > 72) {
            throw new InvalidArgumentException('The password supplied is invalid.');
        }
        return \password_hash($password, $this->passwordAlgo, $this->options);
    }

    /**
     * Verify the password matches the hash provided.
     *
     * @param string $password The password check.
     * @param string $hash     The hash to check against.
     *
     * @return bool Returns true if the password matches the given hash else return false.
     */
    public function verify(string $password, string $hash): bool
    {
        return \password_verify($password, $hash);
    }

    /**
     * Determine if the hash needs a rehash.
     *
     * @param string $hash The hash to check.
     *
     * @return bool Returns true if the hash needs a rehash and false if not.
     */
    public function needsRehash(string $hash): bool
    {
        return \password_needs_rehash($hash, $this->passwordAlgo, $this->options);
    }

    /**
     * Configure the hasher options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'memory_cost' => \PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost'   => \PASSWORD_ARGON2_DEFAULT_TIME_COST,
            'threads'     => \PASSWORD_ARGON2_DEFAULT_THREADS,
            'cost'        => 10,
        ]);
        $resolver->setAllowedTypes('memory_cost', 'int');
        $resolver->setAllowedTypes('time_cost', 'int');
        $resolver->setAllowedTypes('threads', 'int');
        $resolver->setAllowedTypes('cost', 'int');
    }
}
