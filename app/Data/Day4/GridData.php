<?php

namespace App\Data\Day4;

use Exception;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class GridData extends Data
{
    public function __construct(
        public int $width,
        public int $height,
        /* @var Collection<int, Collection<int, string>> */
        private readonly Collection $grid,
    ) {}

    public function __toString(): string
    {
        return $this->grid
            ->map(fn (Collection $row) => $row->join(''))
            ->join("\n");
    }

    /**
     * @param  callable(string $cellValue, int $x, int $y): void  $callback
     *
     * @throws Exception
     */
    public function each(callable $callback): void
    {
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                $callback($this->getCell($x, $y), $x, $y);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function getCell(int $x, int $y): string
    {
        if (! $this->isInBounds($x, $y)) {
            throw new Exception("Coordinates ({$x}, {$y}) are out of bounds (width: {$this->width}, height: {$this->height})");
        }

        return $this->grid->get($y)->get($x);
    }

    public function setCell(int $x, int $y, string $value): void
    {
        if (! $this->isInBounds($x, $y)) {
            throw new Exception("Coordinates ({$x}, {$y}) are out of bounds (width: {$this->width}, height: {$this->height})");
        }

        $this->grid->get($y)->put($x, $value);
    }

    private function isInBounds(int $x, int $y): bool
    {
        return $x >= 0 && $x < $this->width && $y >= 0 && $y < $this->height;
    }

    /**
     * @return Collection<int, string>
     *
     * @throws Exception
     */
    public function getRow(int $y): Collection
    {
        if (! $this->isInBounds(0, $y)) {
            throw new Exception("Row {$y} is out of bounds (height: {$this->height})");
        }

        return $this->grid->get($y);
    }

    /**
     * @param  Collection<int, string>  $lines
     */
    public static function fromInputLines(Collection $lines): self
    {
        /* @var Collection<int, Collection<int, string>> $grid */
        $grid = $lines->map(fn (string $line) => collect(str_split($line)));

        return new self(
            width: $grid->first()->count(),
            height: $grid->count(),
            grid: $grid,
        );
    }
}
