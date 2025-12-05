<?php

namespace App\Data\Day5;

use App\Data\Day2\NumberRangeData;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class IngredientData extends Data
{
    public function __construct(
        public Collection $availableIngredients,
        public Collection $freshIngredientRanges,
    ) {}

    public function getAvailableFreshIngredients(): Collection
    {
        return $this->availableIngredients->filter(fn (int $ingredient) => $this->freshIngredientRanges->contains(fn (NumberRangeData $range) => $range->containsValue($ingredient)));
    }

    public function getFreshIngredientsCount(): int
    {
        /*  @var int */
        return $this->freshIngredientRanges->reduce(function (int $carry, NumberRangeData $range) {
            return $carry + ($range->end - $range->start + 1);
        }, 0);
    }

    public static function fromInputLines(Collection $lines): self
    {
        $index = $lines->search('');

        /** @var Collection<int, NumberRangeData> $freshIngredientRanges */
        $freshIngredientRanges = $lines->slice(0, $index)
            ->map(fn (string $line) => NumberRangeData::from($line))
            ->sortBy(fn (NumberRangeData $range) => $range->start)
            ->values();

        // Adjust ranges to be non-overlapping
        foreach ($freshIngredientRanges as $index => $freshIngredientRange) {
            $nextRange = $freshIngredientRanges->get($index + 1);

            // No more ranges to check or no overlap
            if (! $nextRange instanceof NumberRangeData || $nextRange->start > $freshIngredientRange->end) {
                continue;
            }

            // Merge overlapping ranges
            if ($freshIngredientRange->start === $nextRange->start) {
                $nextRange->end = max($freshIngredientRange->end, $nextRange->end);
                $freshIngredientRanges->offsetUnset($index);

                continue;
            }

            // Extend the end of the next range if needed
            if ($freshIngredientRange->end > $nextRange->end) {
                $nextRange->end = $freshIngredientRange->end;
            }

            // Adjust the end of the current range to avoid overlap
            $freshIngredientRange->end = $nextRange->start - 1;
        }

        $availableIngredients = $lines->slice($index + 1)
            ->map(fn (string $line) => (int) $line)
            ->values();

        return new self(
            availableIngredients: $availableIngredients,
            freshIngredientRanges: $freshIngredientRanges
        );
    }
}
