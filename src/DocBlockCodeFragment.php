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


/**
 * Class DocBlockCodeFragment
 * @package DocBlockCode
 */
class DocBlockCodeFragment
{

    /** @var int */
    private $type;

    /** @var string */
    private $content;

    /** @var string|null */
    private $name;

    public const POSITION_COMMON = 0;
    public const POSITION_BEGIN = 1;
    public const POSITION_FRAGMENT = 2;
    public const POSITION_END = 3;


    /**
     * DocBlockCodeFragment constructor.
     * @param string $content content of fragment
     * @param int $type type of fragment
     * @param string|null $name name of fragment
     */
    public function __construct(string $content, int $type = self::POSITION_COMMON, ?string $name = null)
    {
        $this->content = $content;
        $this->type = $type ?? self::POSITION_COMMON;
        $this->name = empty($name) ? null : trim($name);
    }

    public function __toString(): string
    {
        return $this->content;
    }

    /**
     * Return code of position type for fragment, if first argument is null, return transform map
     *
     * @param string|null $position name of position
     * @return array|mixed|null
     */
    public static function positions(?string $position = null) {
        $map = [
            'common' => self::POSITION_COMMON,
            'begin' => self::POSITION_BEGIN,
            'fragment' => self::POSITION_FRAGMENT,
            'end' => self::POSITION_END,
        ];
        if ($position === null) {
            return $map;
        }
        return array_key_exists($position, $map) ? $map[$position] : null;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


}