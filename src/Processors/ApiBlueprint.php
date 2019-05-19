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

use DocBlockCode\Utils;

/**
 * Class ApiBlueprint
 * @package DocBlockCode\Processors
 */
class ApiBlueprint extends Common
{
    /**
     * @inheritDoc
     */
    public $annotation = 'apiBlueprint';

    /**
     * @inheritDoc
     */
    public function process(array $contentsList): string {
        $joinedLines = implode(PHP_EOL . PHP_EOL, $contentsList);
        return Utils::unident($joinedLines);
    }

}