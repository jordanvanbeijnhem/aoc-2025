<?php

namespace App\Console\Commands;

use App\Data\Day3\MaxJoltageData;

class Day3Command extends AocCommand
{
    protected $signature = 'aoc:day3';

    protected $description = 'Command description';

    protected function handlePart1(string $input): void
    {
        $lines = $this->getLines($input);
        $outputJoltage = 0;

        foreach ($lines as $line) {
            $joltages = array_map(fn (string $joltage) => (int) $joltage, str_split($line));
            $maxJoltage = $this->findLargestJoltage($joltages, 0, count($joltages) - 1);
            $nextMaxJoltage = $this->findLargestJoltage($joltages, $maxJoltage->index + 1, null);

            $combinedJoltage = (int) "{$maxJoltage->maxJoltage}{$nextMaxJoltage->maxJoltage}";
            $outputJoltage += $combinedJoltage;
        }

        $this->info("Total output joltage: {$outputJoltage}");
    }

    protected function handlePart2(string $input): void
    {
        $lines = $this->getLines($input);
        $outputJoltage = 0;

        foreach ($lines as $line) {
            $joltages = array_map(fn (string $joltage) => (int) $joltage, str_split($line));
            $currentIndex = 0;
            $combinedJoltage = '';
            for ($i = 12; $i > 0; $i--) {
                $maxIndex = strlen($line) - $i;
                $stringToSearch = substr($line, $currentIndex, $maxIndex - $currentIndex + 1);

                $joltages = array_map(fn (string $joltage) => (int) $joltage, str_split($stringToSearch));
                rsort($joltages);
                $combinedJoltage .= $joltages[0];

                $currentIndex += strpos($stringToSearch, $joltages[0]) + 1;
            }
            $outputJoltage += (int) $combinedJoltage;
        }

        $this->info("Total output joltage: {$outputJoltage}");
    }

    private function findLargestJoltage(array $joltages, int $startIndex, ?int $maxIndex): MaxJoltageData
    {
        $joltagesToSearch = array_slice($joltages, $startIndex, $maxIndex ? $maxIndex - $startIndex : null);
        $maxJoltage = max($joltagesToSearch);
        $index = array_search($maxJoltage, $joltages);

        return new MaxJoltageData(maxJoltage: $maxJoltage, index: $index);
    }

    protected function getPart1FilePath(): string
    {
        return 'day3.txt';
    }

    protected function getPart2FilePath(): string
    {
        return 'day3.txt';
    }
}
