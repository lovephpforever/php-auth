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

use Hybridauth\Adapter\AdapterInterface;
use ParagonIE\EasyDB\EasyDB;

/**
 * The social authenticator.
 */
final class Social
{
    /**
     * Construct a new social authenticator.
     *
     * @param \ParagonIE\EasyDB\EasyDB            $connection The database connection.
     * @param \LovePHPForever\Core\Session        $session    A secure session handler.
     * @param \LovePHPForever\Core\PasswordHasher $hasher     The password hasher.
     * @param \LovePHPForever\Core\Utilities      $utilities  The basic class utilities.
     *
     * @return void Returns nothing.
     */
    public function __construct(
        public EasyDB $connection,
        public Session $session,
        public PasswordHasher $hasher,
        public Utilities $utilities
    ) {
        //
    }

    /**
     * Preform a social login using hybird auth.
     *
     * @param \Hybridauth\Adapter\AdapterInterface $adapter The social adapter.
     *
     * @return void Returns nothing.
     */
    public function login(AdapterInterface $adapter): void
    {
        $adapter->authenticate();
        $userProfile = $adapter->getUserProfile();
        $userExists = $this->connection->cell(
            "SELECT count(id) FROM users WHERE email = ?",
            $userProfile->email
        );
        $socialLoginExists = $this->connection->cell(
            "SELECT count(id) FROM social_logins WHERE email = ? AND provider = ?",
            $userProfile->email,
            \get_class($adapter)
        );
        if ($userExists) {
            if (!$socialLoginExists) {
                $this->connection->insert('social_logins', [
                    'provider'     => \get_class($adapter),
                    'access_token' => $adapter->getAccessToken(),
                    'email'        => $userProfile->email
                ]);
                $this->connection->update('users', [
                    'verified' => \true,
                ], [
                    'email' => $userProfile->email
                ]);
            } else {
                $this->connection->update('social_logins', [
                    'access_token' => $adapter->getAccessToken(),
                ], [
                    'email' => $userProfile->email
                ]);
            }
            $redirectTo = '/dashboard';
        } else {
            $password = $this->hasher->compute($this->utilities->generateString(30));
            $this->connection->insert('users', [
                'password' => $password,
                'email'    => $userProfile->email
            ]);
            $this->connection->insert('social_logins', [
                'provider'     => \get_class($adapter),
                'access_token' => $adapter->getAccessToken(),
                'email'        => $userProfile->email
            ]);
            $redirectTo = '/auth/username';
        }
        $this->session->put('logged_in', \true);
        $this->session->put('email', $userProfile->email);
        $this->utilities->redirect($redirectTo);
    }
}
