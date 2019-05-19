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
 * @code:begin
 * This is example of common processor for extract code from comments, this example extract these text:
 *
 * @end
 *
 */
namespace DocBlockCode\Examples\Common;


/**
 * Please mind that annotation order is loosely ordered by type after annotation
 * \@code:begin will be added to begin of extract
 * \@code:end will be added to end of extract
 * \@code:fragment will be ignored to extract but can be used to insert his content to another code by @include annotation
 * Extracted code parts has guaranteed order only by position in file, for parts between files there are not guaranteed order
 *
 * @code:begin
 *
 * I have a pet.
 * @end
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
     * I am good owner
     */
    public function pet(): void
    {
        echo "petting animal";
    }
}
