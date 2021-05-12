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

use ParagonIE\ConstantTime\Binary;
use RangeException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The basic class utilities.
 */
final class Utilities
{
    /** @var array $options The class utilities options. */
    private $options = [];

    /**
     * Construct a class utilities manager.
     *
     * @param array $options The class utilities options.
     *
     * @return void Returns nothing.
     */
    public function __construct(\array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * Removes single and double quotes.
     *
     * @param string      $text           The text to alter.
     * @param int         $flags          A butmask flag alter.
     * @param string|null $ecoding        The alter encoding.
     * @param bool        $doubleEncoding Should we enable double encoding?
     *
     * @return string Returns the altered text.
     */
    public function e(string $text, int $flags = \ENT_QUOTES, string|null $encoding = \null, bool $doubleEncoding = \true): string
    {
        return \htmlspecialchars($string, $flags, $encoding);
    }

    /**
     * Generate a random string.
     *
     * @param int    $length   The length of the random string.
     * @param string $keyspace The allowed characters in the string.
     *
     * @return string Returns the generated string.
     */
    public function generateString(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        if ($length < 1) {
            throw new RangeException('Length must be a positive integer');
        }
        $pieces = [];
        $max = Binary::safeStrlen($keyspace) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[\random_int(0, $max)];
        }
        return \implode('', $pieces);
    }

    /**
     * Redirect the user to a different page.
     *
     * @param string $url        The location where to send the user.
     * @param int    $statusCode The HTTP redirect stats code.
     *
     * @return void Returns nothing.
     */
    public function redirect(string $url, int $statusCode = 303): void
    {
        if (!\headers_sent()) {
            \header('Location: ' . $url, \true, $statusCode);
        } else {
            echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\" />";
            echo "<script type=\"application/javascript\">";
            echo "window.location.replace(\"$url\");";
            echo "</script>";
        }
        exit();
    }

    /**
     * Force the user to use an encrypted connection.
     *
     * @return void Returns nothing.
     */
    public function enforceHttps(): void
    {
        if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== 'on') {
            $this->redirect($this->getBaseUrl() . $_SERVER['REQUEST_URI'], 301);
        }
    }

    /**
     * Get the base url of this web app.
     *
     * @param bool $https Should we include https?
     *
     * @return string Returns the base url.
     */
    public function getBaseUrl($https = \true): string
    {
        return $https ? 'https://' . $this->options['host'] : 'http://' . $this->options['host'];
    }

    /**
     * Configure the class manager options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'host' => 'localhost'
        ]);
        $resolver->setAllowedTypes('host', 'string');
    }
}
