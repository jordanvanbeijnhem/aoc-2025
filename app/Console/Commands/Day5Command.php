<?php

namespace App\Console\Commands;

use App\Data\Day5\IngredientData;

class Day5Command extends AocCommand
{
    protected $signature = 'aoc:day5';

    protected $description = 'Command description';

    protected function handlePart1(string $input): void
    {
        $lines = $this->getLines($input);
        $ingredientData = IngredientData::from($lines);

        $this->info('Amount of available fresh ingredients: '.$ingredientData->getAvailableFreshIngredients()->count());
    }

    protected function handlePart2(string $input): void
    {
        $lines = $this->getLines($input);
        $ingredientData = IngredientData::from($lines);

        $this->info('Total amount of fresh ingredients: '.$ingredientData->getFreshIngredientsCount());
    }

    protected function getPart1FilePath(): string
    {
        return 'day5.txt';
    }

    protected function getPart2FilePath(): string
    {
        return 'day5.txt';
    }
}
