<?php

namespace App\Console\Commands;

use App\Data\Day1\DialInstructionData;
use App\Enums\Day1\DialDirection;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Day1Command extends Command
{
    protected $signature = 'aoc:day1';

    protected $description = 'Command description';

    public function handle(): void
    {
        $this->handlePart2();
    }

    private function handlePart1(): void
    {
        $instructions = $this->getInstructions();

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

    private function handlePart2(): void
    {
        $instructions = $this->getInstructions();

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
    private function getInstructions(): Collection
    {
        $input = Storage::get('day1.txt');
        $lines = collect(explode("\n", rtrim($input)));

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
}
