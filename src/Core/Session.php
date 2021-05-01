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

final class Session
{
/**
     * {@inheritdoc}
     */
    public function exists(): bool
    {
        if (\php_sapi_name() !== 'cli') {
            return session_status() === \PHP_SESSION_ACTIVE ? \true : \false;
        }
        return \false;
    }

    /**
     * {@inheritdoc}
     */
    public function regenerate(bool $deleteOldSession = \true): bool
    {
        return session_regenerate_id($deleteOldSession);
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
}
