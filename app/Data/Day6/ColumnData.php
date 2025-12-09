<?php

namespace App\Data\Day6;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ColumnData extends Data
{
    public function __construct(
        /** @var Collection<int, int> $values */
        public Collection $values,
        public string $operator,
    ) {}

    public function calculate(): int
    {
        return match ($this->operator) {
            '+' => $this->values->sum(),
            '*' => $this->values->reduce(fn (int $carry, int $value) => $carry * $value, 1),
        };
    }
}
