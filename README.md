[![License](https://img.shields.io/badge/license-Apache2.0-blue.svg?style=flat-square)](LICENSE-2.0.txt)

# DocBlock-Code

Extract code block from DocBlock in code and generate file

WARNING: This version hasn't been tested yet. This is an initial commit and is experimental. Use with caution.

## Features

- Extracts text/code from DocBlock
- The command-line interface is available.
- Expandability - you can write custom processors to process extracted code (currently available Common processor for simple text and primitive JSON processor) 

## Installation (with [Composer](https://getcomposer.org))

Composer package will be available after implementing tests.

## Usage

Add annotations to your PHP files.

```php
/**
 * @code
 * Content of exportet code
 */

/**
 * @DocBlockCode/json(
 *    {"property": "value"}
 * )
 */
```

More in [Documentation](#documentation) section

### Usage from php

Generate always-up-to-date documentation.

```php
<?php
require("vendor/autoload.php");
$openapi = new \DocBlockCode\DocBlockCode();
$content = $openapi->parseFiles('/path/to/project');
file_put_contents('your_file.txt', $content);
```

### Usage from the Command Line Interface

Generate the documentation to a static JSON file.

```bash
./vendor/bin/docblock-code --help
```

## <a name="documentation"></a> Documentation

Annotation format for extracting code is

```php
/**
 * @[DocBlockCode/](processor_annotation)[:begin|fragment|end] [fragment_name]
 * Content of exportet code
 * @[DocBlockCode/]include:(fragment_name)
 * [@[DocBlockCode/]end]
 */
``` 

Explanation of parts:
 * **DocBlockCode**  - prefix for annotation name, this prefix is optional, can be used to prevent disruption with another annotation lib in your project
 * **processor_annotation** - name of annotation, this name is save in Processor class, default annotation is `@code`.
 * **type (begin|fragment|end)** - define optional type of code block, there are three types
    * _begin_ - put code block to begin of generated string, this is useful for creating a header of your code anywhere in your application. You can define multiple begin block, however, I suggest use it only once
    * _end_ - same as _begin_ but the content is pushed to end of a generated file
    * _fragment_ - this block is ignored for extracting but can be used for including to another block, including is described below
 * **fragment_name** - name a fragment, is used to include fragment to another block. Can be used without `fragment` type
 * **end** - end annotation for code block. If omitted, a code is extracted to end of DocBlock or to another DocBlockCode annotation
 * **include** - including the content of another block, included block must be named.

If you need put ignored annotation just add ` \ ` before annotation. Eg `\@code`

### Example

```php
<?php

/**
 * Any text out of DocBlockCode annotation will not be included to extraction
 *
 * @code:begin
 * 1. This code block will be at the beginning of extracted code
 * @end
 *
 * @code:fragment include_fragment
 * 3. This block is not extracted automatically but can be included to another block
 * @end
 *
 * @code:end
 * 4. This code will be at the end of extracted code, \@end annotation is omitted
 *
 */
class Pet
{

    /**
     * @param int this param do not interact with DocBlockCode
     *
     * @DocBlockCode/code
     * 2. Block with prefix
     *
     * After this line, there is included named code fragment
     * @include:include_fragment
     */
    public function doSound(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            echo "Woof";
        }
    }
}
```

Will extract:

```
1. This code block will be at the beginning of the extracted code
2. Block with prefix

After this line, there is included named code fragment
3. This block is not extracted automatically but can be included to another block
4. This code will be at the end of the extracted code, \@end annotation is omitted
```

Other examples are available in the Example folder

### Custom processor

You can use a custom processor to process code block to join them to one string
To create your processor, create a class in `DocBlockCode\Processors` namespace and implement `ProcessorInterface`.
Implemented method `process` receive array with `DocBlockCodeFragment` object. This object contain three params:
 * **content** - extracted text
 * **type** - type of fragment (begin|fragment|end|common) represented by contants (note: common fragment is default and is equals annotation without type)
 * **name** - name of fragment

Method `process` must return processed fragments as a string. Method `getAnnotation` must return the name of your processor annotation. Eg if you processor named `Markdown` with annotation `markdown`, you use it in your comment as
```php
/**
 * @markdown
 * Content of exported code
 * @end
 */
```
You can use your processor in code like this

```php
<?php
require("vendor/autoload.php");
$openapi = new \DocBlockCode\DocBlockCode(new \DocBlockCode\Processors\Markdown());
$content = $openapi->parseFiles('/path/to/project');
file_put_contents('your_file.md', $content);
```

and with CLI

```bash
./vendor/bin/docblock-code --processor Markdown ./your_project
```

## Contributing

Feel free to submit [Github Issues](https://github.com/lehovec/docblock-code/issues)
or pull requests.

Make sure pull requests pass [PHPUnit](https://phpunit.de/)
and [PHP_CodeSniffer](https://github.com/cakephp/cakephp-codesniffer) (PSR-2) tests.

To run both unit tests and linting execute:

```bash
composer test
```

Running only unit tests:

```bash
./bin/phpunit
```

Running only linting:

```bash
./bin/phpcs -p --extensions=php --standard=PSR2 --error-severity=1 --warning-severity=0 ./src ./tests
```