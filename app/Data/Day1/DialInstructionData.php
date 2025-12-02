<?php

namespace App\Data\Day1;

use App\Enums\Day1\DialDirection;
use Spatie\LaravelData\Data;

class DialInstructionData extends Data
{
    public function __construct(
        public DialDirection $direction,
        public int $steps,
    ) {}

    public function __toString(): string
    {
        return "{$this->direction->value}{$this->steps}";
    }

    public static function fromString(string $instruction): self
    {
        $direction = substr($instruction, 0, 1);
        $steps = (int) substr($instruction, 1);

        return new self(direction: DialDirection::from($direction), steps: $steps);
    }
}
