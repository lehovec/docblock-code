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

class Json extends Common
{
    /**
     * @inheritDoc
     */
    public $annotation = 'json';

    /**
     * @inheritDoc
     */
    public function process(array $contentsList): string
    {
        while ($item = current($contentsList)) {
            $next = next($contentsList);
            if (!preg_match('/:$/', trim((string)$item))
                && $item->getType() === DocBlockCodeFragment::POSITION_COMMON
                && $next && $next->getType() !== DocBlockCodeFragment::POSITION_END) {
                $item->setContent($item->getContent() . ',');
            }
        }
        return implode('', $contentsList);
    }

}