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

use ParagonIE\EasyDB\EasyDB;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The password hasher.
 */
final class Auth
{
    /** @var array $options The auth options. */
    private $options = [];

    /**
     * Construct a new auth manager.
     *
     * @param \ParagonIE\EasyDB\EasyDB            $connection The database connection.
     * @param \LovePHPForever\Core\Session        $session    The session handler.
     * @param \LovePHPForever\Core\Validator      $validator  The symfony validator.
     * @param \LovePHPForever\Core\PasswordHasher $hasher     The password hasher.
     * @param array                               $options    The auth options.
     *
     * @return void Returns nothing.
     */
    public function __construct(
        public EasyDB $connection,
        public Session $session,
        public Validator $validator,
        public PasswordHasher $hasher,
        array $options = []
    ) {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * Check to see if a user is logged in.
     *
     * @return bool Returns true if the user is logged in and false if not.
     */
    public function loggedIn(): bool
    {
        if (!$this->session->exists()) {
            throw new RuntimeException('There is no active session.');
        }
        return (bool) $this->session->get('auth.logged_in', false);
    }

    /**
     * Log the user in.
     *
     * @return void Returns nothing.
     */
    public function login(string $id, string $password): void
    {
        //
    }

    /**
     * Log the user out.
     *
     * @return bool Returns true if the user has been logged out and false if not.
     */
    public function logout(): bool
    {
        return $this->session->stop();
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
        // $resolver->setDefaults([]);
    }
}
