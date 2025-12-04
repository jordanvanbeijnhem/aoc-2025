<?php

namespace App\Console\Commands;

use App\Data\Day4\PaperRollGrid;
use Exception;

class Day4Command extends AocCommand
{
    public const DIRECTIONS = [
        [0, -1], // Up
        [1, -1], // Up-Right
        [1, 0], // Right
        [1, 1], // Down-Right
        [0, 1], // Down
        [-1, 1], // Down-Left
        [-1, 0], // Left
        [-1, -1], // Up-Left
    ];

    protected $signature = 'aoc:day4';

    protected $description = 'Command description';

    protected function handlePart1(string $input): void
    {
        $lines = $this->getLines($input);
        $grid = PaperRollGrid::from($lines);

        $reachableRollCount = 0;
        $grid->each(function (string $cellValue, int $x, int $y) use ($grid, &$reachableRollCount): void {
            if ($cellValue !== '@') {
                return;
            }

            $adjacentRollCount = 0;
            foreach (self::DIRECTIONS as [$dx, $dy]) {
                if ($adjacentRollCount >= 4) {
                    break;
                }

                try {
                    $neighborValue = $grid->getCell($x + $dx, $y + $dy);
                    if ($neighborValue === '@') {
                        $adjacentRollCount++;
                    }
                } catch (Exception) {
                    // Out of bounds, do nothing
                }
            }

            if ($adjacentRollCount < 4) {
                $reachableRollCount++;
            }
        });

        $this->info("Total reachable paper rolls: {$reachableRollCount}");
    }

    protected function handlePart2(string $input): void
    {
        $lines = $this->getLines($input);
        $grid = PaperRollGrid::from($lines);

        $reachableRollCount = 0;
        while (true) {
            $previousReachableRollCount = $reachableRollCount;
            $grid->each(function (string $cellValue, int $x, int $y) use ($grid, &$reachableRollCount): void {
                if ($cellValue !== '@') {
                    return;
                }

                $adjacentRollCount = 0;
                foreach (self::DIRECTIONS as [$dx, $dy]) {
                    if ($adjacentRollCount >= 4) {
                        break;
                    }

                    try {
                        $neighborValue = $grid->getCell($x + $dx, $y + $dy);
                        if ($neighborValue === '@') {
                            $adjacentRollCount++;
                        }
                    } catch (Exception) {
                        // Out of bounds, do nothing
                    }
                }

                if ($adjacentRollCount < 4) {
                    $reachableRollCount++;
                    $grid->setCell($x, $y, '.');
                }
            });

            if ($previousReachableRollCount === $reachableRollCount) {
                break;
            }
        }

        $this->info("Total reachable paper rolls: {$reachableRollCount}");
    }

    protected function getPart1FilePath(): string
    {
        return 'day4.txt';
    }

    protected function getPart2FilePath(): string
    {
        return 'day4.txt';
    }
}
