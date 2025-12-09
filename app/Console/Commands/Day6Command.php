<?php

namespace App\Console\Commands;

use App\Data\Day6\CephalopodMathSheetData;
use App\Data\Day6\HumanMathSheetData;
use App\Data\Day6\MathSheetData;

class Day6Command extends AocCommand
{
    protected $signature = 'aoc:day6';

    protected $description = 'Command description';

    protected function handlePart1(string $input): void
    {
        $lines = $this->getLines($input);
        $mathSheetData = HumanMathSheetData::from($lines);
        $totalResult = $this->solveMathSheet($mathSheetData);

        $this->info("Grand total of individual solutions: {$totalResult}");
    }

    protected function handlePart2(string $input): void
    {
        $lines = $this->getLines($input);
        $mathSheetData = CephalopodMathSheetData::from($lines);
        $totalResult = $mathSheetData->calculate();

        $this->info($mathSheetData->toJson());

        //        foreach ($mathSheetData->rows as $columns) {
        //            $this->info($column->join(', '));
        //        }

        $this->info("Grand total of individual solutions: {$totalResult}");
    }

    private function solveMathSheet(MathSheetData $mathSheetData): int
    {
        $totalResult = 0;
        for ($i = 0; $i < $mathSheetData->getWidth(); $i++) {
            $result = $mathSheetData->rows->get(0)->get($i);
            $operator = $mathSheetData->operators->get($i);
            for ($j = 1; $j < $mathSheetData->getHeight(); $j++) {
                $number = $mathSheetData->rows->get($j)->get($i);
                match ($operator) {
                    '+' => $result += $number,
                    '*' => $result *= $number,
                };
            }
            $totalResult += $result;
        }

        return $totalResult;
    }

    protected function getPart1FilePath(): string
    {
        return 'day6.txt';
    }

    protected function getPart2FilePath(): string
    {
        return 'day6.txt';
    }
}
