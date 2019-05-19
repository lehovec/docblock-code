<?php
/**
 * DocBlockCode : Put your code to php comment (https://github.com/lehovec/docode)
 * Copyright (c) Jakub Lehovec (https://lehovec.com)
 *
 * Licensed under The Apache License 2.0
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @license Apache 2.0
 *
 */

namespace DocBlockCode;


use InvalidArgumentException;
use Symfony\Component\Finder\Finder;

/**
 * Class Utils
 * @package DocBlockCode
 */
class Utils
{

    /**
     * Unident text, if second attribute is null, try to automatically find size of indentation
     *
     * @param string $string string to unident
     * @param int|null $count how many spaces to unident
     * @return string
     */
    public static function unident(string $string, ?int $count = null): string
    {
        $min = $count;
        if ($count === null) {
            $lines = substr_count($string, "\n");
            preg_match_all('/^\s/m', $string, $match);
            if (count($match[0]) === $lines + 1) {
                $min = min(array_map(function ($item) {
                    return $item === "\n" ? 1 : strlen($item);
                }, $match[0]));
            }
        }
        return preg_replace('/^[^\S\r\n]{0,' . $min . '}/m', '', $string);
    }


    /**
     * Turns the given $fullPath into a relative path based on $basePaths, which can either
     * be a single string path, or a list of possible paths. If a list is given, the first
     * matching basePath in the list will be used to compute the relative path. If no
     * relative path could be computed, the original string will be returned because there
     * is always a chance it was a valid relative path to begin with.
     *
     * It should be noted that these are "relative paths" primarily in Finder's sense of them,
     * and conform specifically to what is expected by functions like `exclude()` and `notPath()`.
     * In particular, leading and trailing slashes are removed.
     *
     * @param  string       $fullPath
     * @param  string|array $basePaths
     * @return string
     */
    public static function getRelativePath(string $fullPath, $basePaths): string
    {
        $relativePath = null;
        if (is_string($basePaths)) { // just a single path, not an array of possible paths
            $relativePath = self::removePrefix($fullPath, $basePaths);
        } else { // an array of paths
            foreach ($basePaths as $basePath) {
                $relativePath = self::removePrefix($fullPath, $basePath);
                if (!empty($relativePath)) {
                    break;
                }
            }
        }
        return !empty($relativePath) ? trim($relativePath, '/') : $fullPath;
    }
    /**
     * Removes a prefix from the start of a string if it exists, or null otherwise.
     *
     * @param  string $str
     * @param  string $prefix
     * @return null|string
     */
    private static function removePrefix(string $str, string $prefix): ?string
    {
        if (substr($str, 0, strlen($prefix)) == $prefix) {
            return substr($str, strlen($prefix));
        }
        return null;
    }

    /**
     * Build a Symfony Finder object that scans the given $directory.
     *
     * @param string|array|Finder $directory The directory(s) or filename(s)
     * @param null|string|array $exclude The directory(s) or filename(s) to exclude (as absolute or relative paths)
     * @param null|string $pattern The pattern of the files to scan
     * @return Finder
     * @throws InvalidArgumentException
     */
    public static function finder($directory, $exclude = null, ?string $pattern = null): Finder
    {
        if ($directory instanceof Finder) {
            return $directory;
        } else {
            $finder = new Finder();
            $finder->sortByName();
        }
        if ($pattern === null) {
            $pattern = '*.php';
        }
        $finder->files()->followLinks()->name($pattern);
        if (is_string($directory)) {
            if (is_file($directory)) { // Scan a single file?
                $finder->append([$directory]);
            } else { // Scan a directory
                $finder->in($directory);
            }
        } elseif (is_array($directory)) {
            foreach ($directory as $path) {
                if (is_file($path)) { // Scan a file?
                    $finder->append([$path]);
                } else {
                    $finder->in($path);
                }
            }
        } else {
            throw new InvalidArgumentException('Unexpected $directory value:' . gettype($directory));
        }
        if ($exclude !== null) {
            if (is_string($exclude)) {
                $finder->notPath(Utils::getRelativePath($exclude, $directory));
            } elseif (is_array($exclude)) {
                foreach ($exclude as $path) {
                    $finder->notPath(Utils::getRelativePath($path, $directory));
                }
            } else {
                throw new InvalidArgumentException('Unexpected $exclude value:' . gettype($exclude));
            }
        }
        return $finder;
    }

}