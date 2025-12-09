<?php

namespace App\Console\Commands;

use App\Data\Day4\GridData;

class Day7Command extends AocCommand
{
    protected $signature = 'aoc:day7';

    protected $description = 'Command description';

    protected function handlePart1(string $input): void
    {
        $lines = $this->getLines($input);
        $grid = GridData::from($lines);
        $firstRow = $grid->getRow(0);
        $startIndex = $firstRow->search('S');
        $beams = collect([$startIndex]); // x positions of beams in the current row
        $splitCount = 0;

        for ($y = 2; $y < $grid->height; $y++) {
            $beamsToAdd = collect();
            $beamsToRemove = collect();
            foreach ($beams as $beam) {
                $char = $grid->getCell($beam, $y);
                if ($char !== '^') {
                    continue;
                }

                // Add new beams to the left and right, remove the current beam
                $beamsToAdd->push($beam - 1);
                $beamsToAdd->push($beam + 1);
                $beamsToRemove->push($beam);
                $splitCount++;
            }

            // Update beams for the next row
            $beams = $beams->diff($beamsToRemove);
            $beams = $beams->merge($beamsToAdd->unique())->unique();
        }

        $this->info("Total splits: {$splitCount}");
    }

    protected function handlePart2(string $input): void
    {
        $lines = $this->getLines($input);
        $grid = GridData::from($lines);
        $firstRow = $grid->getRow(0);
        $startIndex = $firstRow->search('S');

        $this->info('Total unique paths: '.$this->countPaths($grid, $startIndex, 2));
    }

    public function countPaths(GridData $grid, int $startX, int $startY): int
    {
        $w = $grid->width;
        $h = $grid->height;
        $resultArray = array_fill(0, $h, array_fill(0, $w, 0));
        $resultArray[$startY][$startX] = 1;

        for ($y = $startY; $y < $h - 1; $y++) {
            for ($x = 0; $x < $w; $x++) {
                if ($resultArray[$y][$x] === 0) {
                    continue;
                }

                $cell = $grid->getCell($x, $y);

                if ($cell === '^') {
                    $resultArray[$y + 1][$x - 1] += $resultArray[$y][$x];
                    $resultArray[$y + 1][$x + 1] += $resultArray[$y][$x];
                } else {
                    $resultArray[$y + 1][$x] += $resultArray[$y][$x];
                }
            }
        }

        return array_sum($resultArray[$h - 1]);
    }

    protected function getPart1FilePath(): string
    {
        return 'day7.txt';
    }

    protected function getPart2FilePath(): string
    {
        return 'day7.txt';
    }
}
