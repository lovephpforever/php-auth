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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A secure session handler.
 */
final class Session
{
    /** @var array $options The session handlers options. */
    private $options = [];

    /**
     * Construct a new session handler.
     *
     * @param string $sessionName The sessions namespace.
     * @param array  $options     The session handlers options.
     *
     * @return void Returns nothing.
     */
    public function __construct(string $sessionName, array $options = [])
    {
        if (!\headers_sent()) {
            \session_name($sessionName);
        }
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        if (!$this->exists()) {
            $this->start();
        }
    }

    /**
     * Starts or resumes a session.
     *
     * @throws \RuntimeException If the fingerprint supplied is invalid.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function start(): bool
    {
        $session = \session_start();
        if ($this->has('fingerprint')) {
            if (!\hash_equals($this->get('fingerprint'), $this->getFingerprint())) {
                $this->stop();
                throw new RuntimeException('The fingerprint supplied is invalid.');
            }
        } else {
            $this->put('fingerprint', $this->getFingerprint());
        }
        return $session;
    }

    /**
     * Stop a session and destroy all session data.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function stop(): bool
    {
        $_SESSION = [];
        if (\ini_get("session.use_cookies")) {
            $params = \session_get_cookie_params();
            \setcookie(
                \session_name(),
                '',
                \time() - 42000,
                (string) $params["path"],
                (string) $params["domain"],
                (bool) $params["secure"],
                (bool) $params["httponly"],
                ['samesite' => (string) $params["samesite"]]
            );
        }
        return \session_destroy();
    }

    /**
     * Check to see if a session already exists.
     *
     * @return bool Returns true if one exists and false if not.
     */
    public function exists(): bool
    {
        if (\php_sapi_name() !== 'cli') {
            return \session_status() === \PHP_SESSION_ACTIVE ? \true : \false;
        }
        return \false;
    }

    /**
     * Regenerates the session.
     *
     * @param bool $deleteOldSession Whether to delete the old session or not.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function regenerate(bool $deleteOldSession = \true): bool
    {
        return \session_regenerate_id($deleteOldSession);
    }

    /**
     * Check a session variable.
     *
     * @param string $key The session key index.
     *
     * @return bool Returns true if the session variable exists and false if not.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Get a session variable.
     *
     * @param string $key   The session key index.
     * @param mixed  $value The default value to return.
     *
     * @return mixed Returns the session variables value else the default value.
     */
    public function get(string $key, mixed $defaultValue = \null)
    {
        return $this->has($key) ? $_SESSION[$key] : $defaultValue;
    }

    /**
     * Flash a session variable.
     *
     * @param string $key   The session key index.
     * @param mixed  $value The default value to return.
     *
     * @return mixed Returns the session variables value else the default value.
     */
    public function flash(string $key, mixed $defaultValue = \null)
    {
        $value = $this->get($key, $defaultValue);
        $this->delete($key);
        return $value;
    }

    /**
     * Set a session variable.
     *
     * @param string $key   The session key index.
     * @param mixed  $value The value of the session variable.
     *
     * @return void Returns nothing.
     */
    public function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Delete a session variable.
     *
     * @param string $key The session key index.
     *
     * @return void Returns nothing.
     */
    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get the fingerprint from the current accessing user.
     *
     * @return string Returns the session fingerprint.
     */
    private function getFingerprint(): string
    {
        $remoteIp = ($this->options['validate_ip'] && isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '-';
        $userAgent = ($this->options['validate_ua'] && isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '-';
        return \hash('sha512', \sprintf('%s|%s', $remoteIp, $userAgent));
    }

    /**
     * Configure the session handlers options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'fingerprinting' => \true,
            'validate_ip'    => \true,
            'validate_ua'    => \true,
        ]);
        $resolver->setAllowedTypes('fingerprinting', 'bool');
        $resolver->setAllowedTypes('validate_ip', 'bool');
        $resolver->setAllowedTypes('validate_ua', 'bool');
    }
}
