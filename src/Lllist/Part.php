<?php

namespace Lllist;

class Part
{
    const TYPE_FIRST = 0;
    const TYPE_LAST = 1;
    const TYPE_SEPARATOR = 2;
    const TYPE_STRING = 3;
    const TYPE_BUILDER = 4;

    private $type = null;
    private $value = null;

    public function __construct($type, $value = null)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function is($type)
    {
        return $this->type === $type;
    }

    public function hasOutput()
    {
        return !$this->is(self::TYPE_FIRST) || !$this->is(self::TYPE_LAST);
    }

    private $evaluatedValue = null;

    private function lazyValue () {
        if ($this->evaluatedValue) {
            return $this->evaluatedValue;
        }

        $this->evaluatedValue = $this->compile();

        return $this->evaluatedValue;
    }

    private function compile () {
        if ($this->value instanceof Builder) {
            return $this->value->compile();
        }

        if (is_callable($this->value)) {
            $fn = $this->value;

            return $fn();
        }

        return $this->value;
    }

    public function output(): ?string
    {
        return $this->lazyValue();
    }

    public function empty(): bool
    {
        return !$this->hasOutput() || !Utils::relaxedNotEmpty($this->lazyValue());
    }

    public function isValue(): bool
    {
        return !$this->is(self::TYPE_SEPARATOR) && !$this->empty();
    }

    public static function newFirst() {
        return new static(self::TYPE_FIRST);
    }

    public static function newLast() {
        return new static(self::TYPE_LAST);
    }

    public static function newSeparator(string $separator) {
        return new static(self::TYPE_SEPARATOR, $separator);
    }

    /**
     * Returns a instance of Part with TYPE_BUILDER
     *
     * @param $value
     * @return static
     */
    public static function newString($value) {
        return new static(self::TYPE_STRING, $value);
    }

    /**
     * Returns a instance of Part with TYPE_BUILDER
     *
     * @param Builder $value
     * @return static
     */
    public static function newBuilder(Builder $value) {
        return new static(self::TYPE_BUILDER, $value);
    }
}