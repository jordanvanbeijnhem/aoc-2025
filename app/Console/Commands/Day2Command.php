<?php

namespace App\Console\Commands;

use App\Data\Day2\NumberRangeData;
use Illuminate\Support\Collection;

class Day2Command extends AocCommand
{
    protected $signature = 'aoc:day2';

    protected $description = 'Command description';

    protected function handlePart1(string $input): void
    {
        $ranges = $this->getNumberRanges($input);
        $numbersToCheck = $ranges->flatMap(fn (NumberRangeData $range) => $range->getFullRange());

        $score = 0;
        foreach ($numbersToCheck as $numberToCheck) {
            $numberOfDigits = strlen((string) $numberToCheck);
            if ($numberOfDigits % 2 !== 0) {
                continue;
            }

            $parts = str_split((string) $numberToCheck, $numberOfDigits / 2);
            $uniqueParts = array_unique($parts);
            if (count($uniqueParts) === 1) {
                $score += $numberToCheck;
            }
        }

        $this->info("Sum of invalid IDS: {$score}");
    }

    protected function handlePart2(string $input): void
    {
        $ranges = $this->getNumberRanges($input);
        $numbersToCheck = $ranges->flatMap(fn (NumberRangeData $range) => $range->getFullRange());

        $score = 0;
        foreach ($numbersToCheck as $numberToCheck) {
            $numberOfDigits = strlen((string) $numberToCheck);

            $maxDivisor = intdiv($numberOfDigits, 2);
            for ($i = 1; $i <= $maxDivisor; $i++) {
                if ($numberOfDigits % $i !== 0) {
                    continue;
                }

                $parts = str_split((string) $numberToCheck, $i);
                $uniqueParts = array_unique($parts);
                if (count($uniqueParts) === 1) {
                    $score += $numberToCheck;
                    break;
                }
            }
        }

        $this->info("Sum of invalid IDS: {$score}");
    }

    /**
     * @return Collection<NumberRangeData>
     */
    private function getNumberRanges(string $input): Collection
    {
        $rangeStrings = collect(explode(',', $input));

        return NumberRangeData::collect($rangeStrings);
    }

    protected function getPart1FilePath(): string
    {
        return 'day2.txt';
    }

    protected function getPart2FilePath(): string
    {
        return 'day2.txt';
    }
}
