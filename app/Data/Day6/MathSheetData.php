<?php

namespace App\Data\Day6;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

abstract class MathSheetData extends Data
{
    public function __construct(
        /** @var Collection<int, Collection<int, int>> */
        public Collection $rows,
        /** @var Collection<int, string> */
        public Collection $operators,
    ) {}

    public function getWidth(): int
    {
        return $this->rows->first()->count();
    }

    public function getHeight(): int
    {
        return $this->rows->count();
    }

    abstract public static function fromInputLines(Collection $lines): self;
}
