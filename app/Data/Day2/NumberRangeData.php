<?php

namespace App\Data\Day2;

use Spatie\LaravelData\Data;

class NumberRangeData extends Data
{
    public function __construct(
        public int $start,
        public int $end,
    ) {}

    public function __toString(): string
    {
        return "{$this->start}-{$this->end}";
    }

    public function getFullRange(): array
    {
        return range($this->start, $this->end);
    }

    public static function fromString(string $range): self
    {
        [$start, $end] = explode('-', $range);

        return new self(start: (int) $start, end: (int) $end);
    }
}
