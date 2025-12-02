<?php

namespace App\Console\Commands;

use App\Data\Day2\NumberRangeData;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Day2Command extends Command
{
    protected $signature = 'aoc:day2';

    protected $description = 'Command description';

    public function handle(): void
    {
        $this->handlePart2();
    }

    private function handlePart1(): void
    {
        $ranges = $this->getNumberRanges();
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

    private function handlePart2(): void
    {
        $ranges = $this->getNumberRanges();
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
    private function getNumberRanges(): Collection
    {
        $input = Storage::get('day2.txt');
        $rangeStrings = collect(explode(',', trim($input)));

        return NumberRangeData::collect($rangeStrings);
    }
}
