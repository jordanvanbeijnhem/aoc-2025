<?php

namespace App\Data\Day6;

use App\Data\Day6\MathSheetData;
use Illuminate\Support\Collection;

class HumanMathSheetData extends MathSheetData
{
    public static function fromInputLines(Collection $lines): self
    {
        $rows = $lines->splice(0, $lines->count() - 1)
            ->map(fn (string $line) => self::parseLine($line)->map(fn (string $num) => (int) $num));
        $operators = self::parseLine($lines->last());

        return new self(
            rows: $rows,
            operators: $operators,
        );
    }

    private static function parseLine(string $line): Collection
    {
        $result = collect();
        $previousChar = null;
        $temp = '';
        foreach (str_split($line) as $char) {
            if ($char === ' ' && $previousChar !== ' ' && $temp !== '') {
                $result->push($temp);
                $temp = '';
            }

            $previousChar = $char;
            if ($char === ' ') {
                continue;
            }

            $temp .= $char;
        }

        if ($temp !== '') {
            $result->push($temp);
        }

        return $result;
    }
}
