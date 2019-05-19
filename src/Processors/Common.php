<?php

namespace DocBlockCode\Processors;

class Common implements ProcessorInterface
{
    /**
     * @inheritDoc
     */
    public $annotation = 'code';

    /**
     * @inheritDoc
     */
    public function process(array $contentsList): string {
        return implode(PHP_EOL, $contentsList);
    }

    /**
     * @inheritDoc
     */
    public function getAnnotation(): string
    {
        return $this->annotation;
    }
}