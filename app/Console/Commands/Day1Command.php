<?php

namespace App\Console\Commands;

use App\Data\Day1\DialInstructionData;
use App\Enums\Day1\DialDirection;
use Illuminate\Support\Collection;

class Day1Command extends AocCommand
{
    protected $signature = 'aoc:day1';

    protected $description = 'Command description';

    protected function handlePart1(string $input): void
    {
        $instructions = $this->getInstructions($input);

        $currentPosition = 50;
        $positionZeroCount = 0;
        foreach ($instructions as $instruction) {
            if ($instruction->steps === 0) {
                continue;
            }

            $currentPosition = $this->applyInstruction($currentPosition, $instruction);
            $currentPosition = $this->normalizePosition($currentPosition);

            if ($currentPosition === 0) {
                $positionZeroCount++;
            }
        }

        $this->info("Total times at position zero: {$positionZeroCount}");
    }

    protected function handlePart2(string $input): void
    {
        $instructions = $this->getInstructions($input);

        $currentPosition = 50;
        $positionZeroCount = 0;
        foreach ($instructions as $instruction) {
            if ($instruction->steps === 0) {
                continue;
            }

            $startPosition = $currentPosition;
            $currentPosition = $this->applyInstruction($currentPosition, $instruction);

            $positionZeroCount += intdiv(abs($currentPosition), 100);

            if ($currentPosition <= 0 && $startPosition > 0) {
                $positionZeroCount += 1;
            }

            $currentPosition = $this->normalizePosition($currentPosition);
        }

        $this->info("Total times at position zero: {$positionZeroCount}");
    }

    /**
     * @return Collection<DialInstructionData>
     */
    private function getInstructions(string $input): Collection
    {
        $lines = $this->getLines($input);

        return DialInstructionData::collect($lines);
    }

    private function applyInstruction(int $currentPosition, DialInstructionData $instruction): int
    {
        $steps = $instruction->steps;

        if ($instruction->direction === DialDirection::LEFT) {
            $steps = -$steps;
        }

        return $currentPosition + $steps;
    }

    private function normalizePosition(int $position): int
    {
        $position = $position % 100;

        if ($position < 0) {
            return $position + 100;
        }

        return $position;
    }

    protected function getPart1FilePath(): string
    {
        return 'day1.txt';
    }

    protected function getPart2FilePath(): string
    {
        return 'day1.txt';
    }
}
