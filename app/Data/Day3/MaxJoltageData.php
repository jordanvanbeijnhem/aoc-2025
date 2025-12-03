<?php

namespace App\Data\Day3;

use Spatie\LaravelData\Data;

class MaxJoltageData extends Data
{
    public function __construct(
        public int $maxJoltage,
        public int $index,
    ) {}

    public function __toString(): string
    {
        return "{$this->maxJoltage} (Index: {$this->index})";
    }
}
