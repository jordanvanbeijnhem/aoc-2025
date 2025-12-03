<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

abstract class AocCommand extends Command
{
    protected bool $exampleMode = false;

    public function handle(): void
    {
        $this->exampleMode = confirm('Run in example mode?', default: false);
        $partToRun = select('Which part do you want to run?', options: [
            1 => 'Part 1',
            2 => 'Part 2',
        ], default: 1);

        $filepath = $partToRun === 1
            ? $this->getPart1FilePath()
            : $this->getPart2FilePath();

        if ($this->exampleMode) {
            $filepath = "examples/{$filepath}";
        }

        $file = Storage::get($filepath);
        if ($file === null) {
            $this->error("File {$filepath} not found");
        }
        $file = rtrim($file);

        $partToRun === 1
            ? $this->handlePart1($file)
            : $this->handlePart2($file);
    }

    protected function getLines(string $input): Collection
    {
        return collect(explode("\n", $input));
    }

    abstract protected function getPart1FilePath(): string;

    abstract protected function getPart2FilePath(): string;

    abstract protected function handlePart1(string $input): void;

    abstract protected function handlePart2(string $input): void;
}
