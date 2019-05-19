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

use DocBlockCode\Processors\Common;
use DocBlockCode\Processors\ProcessorInterface;

/**
 * Class DocBlockCode
 *
 * @package DocBlockCode
 */
class DocBlockCode
{
    /**
     * @var Common|null
     */
    private $processor = null;

    /**
     * DocBlockCode constructor.
     * @param ProcessorInterface|null $processor processor to process extracted code from comments
     */
    public function __construct(?ProcessorInterface $processor = null)
    {
        $this->processor = $processor ?? new Common();
    }


    /**
     * Parse file paths and return processed text
     *
     * @param array $paths paths to process
     * @param string $pattern pattern of file names to include
     * @param array $exclude files and folders to exclude
     * @return string
     */
    public function parseFiles(array $paths, string $pattern = '/^.+\.php$/i', ?array $exclude = null): string
    {
        // get all files to process
        /** @var string[] $files */
        $files = [];
        foreach ($paths as $path) {
            $files = array_merge($files, $this->getFilesInFolder($path, $pattern, $exclude));
        }

        // get code from all files and return array of DocBlockCodeFragment instances
        /** @var DocBlockCodeFragment[] $contents */
        $contents = [];
        foreach ($files as $file) {
            $contents = array_merge($contents, $this->getCode($file));
        }
        $contents = $this->insertsFragments($contents);
        $contents = $this->extractAndSort($contents);

        $result = $this->processor->process($contents);

        return $result;
    }

    /**
     * Extract code from file comments
     *
     * @param string $file file path to extract
     * @return DocBlockCodeFragment[]
     */
    private function getCode(string $file): array
    {
        $annotation = $this->processor->getAnnotation();
        $fileContent = file_get_contents($file);

        $tokens = token_get_all($fileContent);
        $comment = [
            T_COMMENT,
            T_DOC_COMMENT
        ];

        $file = [];
        foreach ($tokens as $token) {
            // process only comments
            if (!in_array($token[0], $comment)) {
                continue;
            }

            if (preg_match('/@(DocBlockCode\/)?' . $annotation . '/', $token[1])) {
                // This regex remove php DocBlock characters
                $content = preg_replace('/(^\s{0,}\/?\*+(?!\/))|(\*+\/\s{0,}$)/m', '', $token[1]);
                // remove identation
                $content = Utils::unident($content, 1);
                // Regex for match start of extracted test
                $startAnnotation = '(?:(?<=(?<!\\\)@' . $annotation . ')|(?<=(?<!\\\)@DocBlockCode\/' . $annotation . '))';
                // Regex for match end of extracted test
                $endAnnotation = '(?=(?:(?<!\\\)@end|(?<!\\\)@' . $annotation . '|(?<!\\\)@DocBlockCode\/' . $annotation . '|$))';
                // Regex to extract type of extraction
                $typeAnnotation = '(?::)?(begin|fragment|end)';
                // Regex to extract name of extraction
                $nameAnnotation = '((?:(?<!\n)[^\S\r\n]+)\S+)?';
                preg_match_all('/' . $startAnnotation . $typeAnnotation . '?\s{0,}' . $nameAnnotation . '\s{0,}\n(.*?)(?:\n{0,}\s{0,})' . $endAnnotation . '/s', $content, $matches, PREG_SET_ORDER);

                foreach ($matches as $match) {
                    [$full, $type, $name, $content] = $match;
                    $type = empty($type) ? DocBlockCodeFragment::POSITION_COMMON : DocBlockCodeFragment::positions($type);
                    $file[] = new DocBlockCodeFragment($content, $type, $name);
                }
            }
        }

        return $file;
    }

    /**
     * Get all files in folder and their children
     *
     * @param string $path path to folder
     * @param string $pattern pattern of file names to include
     * @param array $exclude files and folders to exclude
     * @return array array of files in folder in any depth of folder
     */
    private function getFilesInFolder(string $path, string $pattern = '/^.+\.php$/i', ?array $exclude = null): array
    {
        $files = [];
        foreach (Utils::finder($path, $exclude, $pattern) as $item) {
            $files[] = $item->getPathname();
        }
        return $files;

    }

    /**
     * Put begin, end and other items of extracted text to correct order and remove Fragment types
     *
     * @param $contents
     * @return array
     */
    private function extractAndSort($contents): array
    {
        $begins = array_filter($contents, function (DocBlockCodeFragment $item) {
            return $item->getType() === DocBlockCodeFragment::POSITION_BEGIN;
        });
        $parts = array_filter($contents, function (DocBlockCodeFragment $item) {
            return $item->getType() === DocBlockCodeFragment::POSITION_COMMON;
        });
        $ends = array_filter($contents, function (DocBlockCodeFragment $item) {
            return $item->getType() === DocBlockCodeFragment::POSITION_END;
        });
        $contents = array_merge($begins, $parts, $ends);
        return $contents;
    }

    /**
     * Insert fragments to code by @include annotation
     *
     * @param $contents
     * @return DocBlockCodeFragment[]
     */
    private function insertsFragments($contents): array
    {
        // map names and content to easier inserting
        $map = [];
        foreach ($contents as $item) {
            $map[$item->getName()] = $item->getContent();
        }

        do {
            // check if there is still some parts to insert
            $existsMatch = false;
            foreach ($contents as &$content) {
                $existsMatch = $existsMatch || $x = preg_match_all('/(?:(?<=@include:)|(?<=@DocBlockCode\/include:))\S+/im', $content, $matches, PREG_PATTERN_ORDER);
                // remove empty matches
                $matches = array_filter($matches, function ($item) {
                    return !empty($item);
                });
                foreach ($matches[0] ?? [] as $match) {
                    $toReplace = $map[$match] ?? '';
                    if ($match === $content->getName()) {
                        $toReplace = '';
                    }
                    $content->setContent(preg_replace('/@(DocBlockCode\/)?include:' . preg_quote($match) . '/im', $toReplace, $content));
                    if (array_key_exists($content->getName(), $map)) {
                        $map[$content->getName()] = $content->getContent();
                    }
                }
            }
        } while ($existsMatch);

        return $contents;
    }
}
