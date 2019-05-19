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
namespace DocBlockCode\Examples\Json;


/**
 * You can create processors for any type of format or file
 *
 * @json:begin
 * {
 *  "content":
 * @end
 *
 *
 * @json:end
 * }
 *
 */
class Pet
{

    /**
     * This is example of json content, json processor take all parts of your code and return json data
     * WARNING: this is still a simple joining string from comments, json processor cant validate and repair broken json files
     *
     * @DocBlockCode/json
     * {"data": 123}
     */
    public function json(): void
    {
        /**
         * @json
         * "sound": "woof"
         */
        echo json_encode(["content" => ["test" => 1]]);
    }
}
