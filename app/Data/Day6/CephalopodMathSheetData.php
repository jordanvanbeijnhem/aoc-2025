<?php

namespace App\Data\Day6;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class CephalopodMathSheetData extends Data
{
    public function __construct(
        /** @var Collection<int, ColumnData> $columns */
        public Collection $columns
    ) {}

    public function calculate(): int
    {
        /** @var int */
        return $this->columns->reduce(fn (int $carry, ColumnData $column) => $carry + $column->calculate(), 0);
    }

    public static function fromInputLines(Collection $lines): self
    {
        $rowLengths = $lines->map(fn (string $row) => strlen($row));
        $width = $rowLengths->max();
        $height = $lines->count();

        $columns = collect();
        $columnValues = collect();
        $operator = $lines->last()[0];
        $previousColumnWasEmpty = false;
        for ($i = 0; $i < $width; $i++) {
            $columnIsEmpty = self::isColumnEmpty($lines, $i);

            if ($columnIsEmpty && ! $previousColumnWasEmpty) {
                $columns->push(new ColumnData(
                    values: $columnValues,
                    operator: $operator,
                ));

                $columnValues = collect();
                $operator = null;
            }

            if ($columnIsEmpty) {
                $previousColumnWasEmpty = true;

                continue;
            }

            if ($previousColumnWasEmpty) {
                $operator = $lines->last()[$i];
            }

            $temp = '';
            for ($j = 0; $j < $height - 1; $j++) {
                $currentChar = $lines->get($j)[$i] ?? ' ';
                if ($currentChar === ' ') {
                    continue;
                }

                $temp .= $currentChar;
            }

            $columnValues->push((int) $temp);
            $previousColumnWasEmpty = false;
        }

        if ($columnValues->isNotEmpty() && $operator !== null) {
            $columns->push(new ColumnData(
                values: $columnValues,
                operator: $operator,
            ));
        }

        return new self(
            columns: $columns,
        );
    }

    private static function isColumnEmpty(Collection $rows, int $columnIndex): bool
    {
        foreach ($rows as $row) {
            if (($row[$columnIndex] ?? ' ') !== ' ') {
                return false;
            }
        }

        return true;
    }
}
