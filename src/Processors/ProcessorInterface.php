<?php
/**
 * DoCode : Put your code to php comment (https://github.com/lehovec/docode)
 * Copyright (c) Jakub Lehovec (https://lehovec.com)
 *
 * Licensed under The Apache License 2.0
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @license Apache 2.0
 *
 */

namespace DocBlockCode\Processors;

use DocBlockCode\DocBlockCodeFragment;

/**
 * Interface ProcessorInterface
 * Define processor for DocBlock codes
 * @package DocBlockCode\Processors
 */
interface ProcessorInterface
{

    /**
     * Process method, for list of DocBlockCodeFragment array and return processed string
     *
     * @param DocBlockCodeFragment[] $list
     * @return string
     */
    public function process(array $list): string;

    /**
     * Return name of annotation for define code blocks
     *
     * @return string
     */
    public function getAnnotation(): string;
}