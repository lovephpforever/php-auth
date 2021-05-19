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
use ParagonIE\ConstantTime\Base64UrlSafe;
use RuntimeException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use UnderflowException;

/**
 * The csrf handler.
 */
final class Csrf implements CsrfProtector
{
    /**
     * Construct a csrf handler.
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session The session handler.
     * @param \LovePHPForever\Core\Helper                                $helper  The helper class.
     *
     * @return void Returns nothing.
     */
    public function __construct(
        public SessionInterface $session,
        public Helper $helper
    ) {
        if (!$session->isStarted()) {
            throw new RuntimeException('No session was ever started.');
        }
    }

    /**
     * Generate a new secure csrf token.
     *
     * @return string Returns the secure csrf token.
     */
    public function generate(): string
    {
        return Base64UrlSafe::encode(\random_bytes(33));
    }

    /**
     * Resets a new csrf token per each request.
     *
     * @return string Returns the secure csrf token.
     */
    public function initialize(): string
    {
        $token = $this->generate();
        $this->session->set('token', $token);
        $accessedFrom = isset($_SERVER['REQUEST_URI'])
            ? (string) $_SERVER['REQUEST_URI']
            : (string) $_SERVER['SCRIPT_NAME'];
        $this->session->set('accessed_from', $accessedFrom);
        return $token;
    }

    /**
     * Generate a custom csrf field based on the token passed.
     *
     * @return string Returns the custom csrf field.
     */
    public function generateField(string $token): string
    {
        $html = "<input type=\"hidden\" name=\"token\" value=\"";
        $html .= $this->helper->e($token);
        $html .= "\" />";
        return $html;
    }

    /**
     * Verify that the request token matches the stored csrf token.
     *
     * @return void Returns nothing.
     */
    public function verify(array $postData): void
    {
        if (!isset($postData['token'])) {
            throw new UnderflowException('No token was supplied.');
        } elseif (!$this->session->has('token')) {
            throw new UnderflowException('No token was ever stored.');
        } else {
            $accessedFrom = isset($_SERVER['REQUEST_URI'])
                ? (string) $_SERVER['REQUEST_URI']
                : (string) $_SERVER['SCRIPT_NAME'];
            if (\hash_equals((string) $postData['token'], (string) $this->session->get('token'))) {
                if (!\hash_equals((string) $accessedFrom, (string) $this->session->get('accessed_from'))) {
                    throw new RuntimeException('The stored uri does not match the one provided.');
                }
            } else {
                throw new InvalidArgumentException('The token supplied is invalid.');
            }
        }
    }
}
