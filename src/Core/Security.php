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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\RateLimiter\LimiterInterface;

/**
 * The password hasher.
 */
final class Security implements Protector
{
    /**
     * Construct a new password hasher.
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session       The session handler.
     * @param \LovePHPForever\Core\CsrfProtector                         $csrfProtector The csrf protector.
     * @param \Symfony\Component\RateLimiter\LimiterInterface            $rateLimiter   The symfony rate limiter.
     *
     * @return void Returns nothing.
     */
    public function __construct(
        public SessionInterface $session,
        public CsrfProtector $csrfProtector,
        public LimiterInterface $rateLimiter
    ) {
        //
    }

    /**
     * Check to see if too many requests have been made to the server.
     *
     * @return void Returns nothing.
     */
    public function hasTooManyServerRequestsAndConsume(): bool
    {
        if (headers_sent()) {
            throw new RuntimeException('Headers already sent.');
        }
        $limit = $this->limiter->consume();
        \header('X-RateLimit-Remaining: ' . (string) $limit->getRemainingTokens());
        \header('X-RateLimit-Retry-After: ' . (string) $limit->getRetryAfter()->getTimestamp());
        \header('X-RateLimit-Limit: ' . (string) $limit->getLimit());
        return \false === $limit->isAccepted();
    }

    /**
     * Code to run before processing a request.
     *
     * @param array $postData The post data array.
     *
     * @return void Returns nothing.
     */
    public function requestPreRun(array $postData): void
    {
        $this->csrfProtector->verify($postData);
        if ($this->hasTooManyServerRequestsAndConsume()) {
            throw new RuntimeException('Too many requests have been made to the server.');
        }
    }

    /**
     * Apply security headers.
     *
     * @param bool $onlyApplyToLoggedInUsers Should we only apply security headers to logged in users.
     *
     * @return void Returns nothing.
     */
    public function applySecurityHeaders(bool $onlyApplyToLoggedInUsers = \true): void
    {
        if (headers_sent()) {
            throw new RuntimeException('Headers already sent.');
        } elseif ((bool) $this->session->get('logged_in', \false) || !$onlyApplyToLoggedInUsers) {
			\header('X-Frame-Options: sameorigin');
			\header('X-Content-Type-Options: nosniff');
			\header('Cache-Control: no-store, no-cache, must-revalidate', \true);
			\header('Expires: Thu, 19 Nov 1981 00:00:00 GMT', \true);
			\header('Pragma: no-cache', \true);
		}
    }

    /**
     * Modify session config to be more secure.
     * All web applications should be using https no excuse.
     * Cookie SameSite Options:
     *     - "Lax"
     *     - "Strict"
     *
     * @param string $cookieSameSite Should the cookie be restricted to a first-party or same-site context?
     * @param bool   $enforceHttps   Should the cookie be transmitted over a secure connection.
     *
     * @return void Returns nothing.
     */
    public function enforceSessionSecurityConfig(bool $cookieSameSite = "Lax", bool $enforceHttps = true): void
    {
        \ini_set('session.cookie_secure', $enforceHttps);
        \ini_set('session.cookie_httponly', \true);
        \ini_set('session.cookie_samesite', $cookieSameSite);
        \ini_set('session.use_cookies', \true);
        \ini_set('session.use_only_cookies', \true);
        \ini_set('session.use_trans_sid', \false);
    }
}
