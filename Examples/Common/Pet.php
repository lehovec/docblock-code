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
namespace DocBlockCode\Examples\Common;


/**
 * Comments is not linked with code, so you can add code comments to any position in file
 *
 * @code:begin
 *
 * I'll give you a question.
 * @end
 *
 *
 * @code:end
 * What animal do I mean?
 *
 */
class Pet
{

    /**
     * DocBlockCode process only code between code annotations and end of comment or @end annotation
     *
     * @param int count
     *
     * If your project use annotation with same name, you can prefix your code annotations with \@DocBlockCode/ prefix
     * @DocBlockCode/code
     * This pet doing
     *
     * @include:pet_sound
     */
    public function doSound(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            /**
             * @code:fragment pet_sound
             * Woof Woof
             */
            echo "Woof";
        }
    }
}
