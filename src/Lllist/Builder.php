<?php

namespace Lllist;

class Builder
{
    /**
     * @var Part[]
     */
    private $parts = [];

    /**
     * @var Part
     */
    private $defaultSeparator = null;

    /**
     * @var Part
     */
    private $defaultLastSeparator = null;

    public function __construct(string $defaultSeparator = " ", ?string $defaultLastSeparator = null)
    {
        $this->defaultSeparator = Part::newSeparator($defaultSeparator);

        if ($defaultLastSeparator !== null) {
            $this->defaultLastSeparator = Part::newSeparator($defaultLastSeparator);
        }
    }

    public function usingSeparator(string $separator): self
    {
        $this->defaultSeparator = Part::newSeparator($separator);

        return $this;
    }

    public function usingLastSeparator(?string $separator = null): self
    {
        $this->defaultLastSeparator = Part::newSeparator($separator);

        return $this;
    }

    public function count(): int
    {
        return count(array_filter($this->parts, function (Part $part) {
            return $part->isValue();
        }));
    }

    public function shift($y): self
    {
        if ($this->count() <= $y) {
            return $this;
        }

        $shifted = 0;

        while ($this->count() > $y) {
            $part = array_pop($this->parts);
            if ($part && $part->isValue()) {
                $shifted += 1;
            }
        }

        return $this;
    }

    /**
     * @param int $n
     * @param Builder $builder
     * @return $this
     */
    public function truncate($n, Builder $builder)
    {
        return $this->merge($builder->shift($n));
    }

    public function append($value)
    {
        if ($value instanceof Builder) {
            return $this->merge($value);
        }

        if (Utils::relaxedNotEmpty($value)) {
            $this->parts[] = Part::newString($value);
            $this->parts[] = $this->defaultSeparator;
        }

        return $this;
    }

    public function items(array $items)
    {
        foreach ($items as $item) {
            $this->append($item);
        }

        return $this;
    }

    public function itemsf($format, array $items, ?callable $callable = null) {
        foreach ($items as $item) {
            $this->appendf($format, $item, $callable);
        }

        return $this;
    }

    public function appendf($format, $value, ?callable $validator = null)
    {
        if ($validator === null) {
            $validator = [Utils::class, 'relaxedNotEmpty'];
        }

        if ($validator($value)) {
            $this->parts[] = Part::newString(sprintf($format, $value));
            $this->parts[] = $this->defaultSeparator;
        }

        return $this;
    }

    public function strictAppendf($format, $value, ?callable $validator = null)
    {
        if ($validator === null) {
            $validator = [Utils::class, 'notEmpty'];
        }

        return $this->appendf($format, $value, $validator);
    }

    public function pluralizef($value, $singularFormat, $pluralFormat, $noneFormat = null)
    {
        if ($value < 1) {
            return $this->appendf($noneFormat, $value);
        } elseif ($value === 1) {
            return $this->appendf($singularFormat, $value);
        } elseif ($value > 1) {
            return $this->appendf($pluralFormat, $value);
        }

        return $this;
    }

    public function pluralize($value, $singular, $plural, $none = null)
    {
        if ($value < 1) {
            return $this->append($none);
        } elseif ($value === 1) {
            return $this->append($singular);
        } elseif ($value > 1) {
            return $this->append($plural);
        }

        return $this;
    }

    /**
     * Merges a StringBuilder into another
     *
     * @param Builder $builder other StringBuilder
     * @param null|string $leftSeparator if null leftSeparator will be the receptor's separator
     * @param null|string $rightSeparator if null rightSeparator will be the receptor's separator
     * @return $this
     */
    public function merge(Builder $builder, $leftSeparator = null, $rightSeparator = null)
    {
        if ($leftSeparator) {
            $this->parts = $this->popLastSeparator($this->parts);

            $this->parts[] = Part::newSeparator($leftSeparator);
        }

        $this->parts[] = Part::newBuilder($builder);

        if ($rightSeparator) {
            $this->parts[] = Part::newSeparator($rightSeparator);
        } else {
            $this->parts[] = $this->defaultSeparator;
        }

        return $this;
    }

    public function compile(): ?string
    {
        $parts = $this->trimEmptyValues($this->parts);

        if (!count($parts)) {
            return null;
        }

        if ($this->defaultLastSeparator) {
            $parts = $this->replaceLastSeparator($parts);
        }

        $output = array_reduce($parts, function ($all, Part $part) {
            if (!$part->hasOutput()) {
                return $all;
            }

            $out = $part->output();

            if ($out === null) {
                // Removes possible previous separator
                array_pop($all);
            } else {
                $all[] = $out;
            }

            return $all;
        }, []);

        if (!count($output)) {
            return null;
        }

        return join("", $output);
    }

    public function sep($separator)
    {
        $result = Utils::lastIndexOf($this->parts, function (Part $part) {
            return $part->is(Part::TYPE_SEPARATOR);
        });

        if ($result && list($index) = $result) {
            $this->parts[$index] = Part::newSeparator($separator);
        }

        return $this;
    }

    private function replaceLastSeparator($parts)
    {
        $result = Utils::lastIndexOf($parts, function (Part $part) {
            return $part->is(Part::TYPE_SEPARATOR);
        });

        if ($result && list($index) = $result) {
            $this->parts[$index] = $this->defaultLastSeparator;
        }

        return $parts;
    }

    private function popLastSeparator($parts)
    {
        /** @var Part $part */
        $part = Utils::last($parts);

        if ($part && $part->is(Part::TYPE_SEPARATOR)) {
            array_pop($parts);
        }

        return $parts;
    }


    private function trimEmptyValues($parts)
    {
        /** @var Part $part */
        $part = Utils::last($parts);

        if ($part && !$part->isValue()) {
            array_pop($parts);

            $this->trimEmptyValues($parts);
        }

        $part = Utils::head($parts);

        if ($part && !$part->isValue()) {
            array_shift($parts);

            $this->trimEmptyValues($parts);
        }

        return $parts;
    }

}