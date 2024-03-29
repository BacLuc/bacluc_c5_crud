<?php

namespace BaclucC5Crud\Test\Constraints;

use PHPUnit\Framework\Constraint\Constraint;

use function BaclucC5Crud\Lib\collect;
use function mb_strpos;

/**
 * Constraint that asserts that the string it is evaluated for contains
 * a given string.
 *
 * Uses mb_strpos() to find the position of the string in the input, if not
 * found the evaluation fails.
 *
 * The sub-string is passed in the constructor.
 */
class StringContainsAll extends Constraint {
    /**
     * @var string
     */
    private $strings;

    /**
     * @var bool
     */
    private $ignoreCase;

    public function __construct(array $strings, bool $ignoreCase = false) {
        $this->strings = $strings;
        $this->ignoreCase = $ignoreCase;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string {
        if ($this->ignoreCase) {
            $strings = collect($this->strings)->map(function ($string) {
                return \mb_strtolower($string);
            });
        } else {
            $strings = collect($this->strings);
        }

        return \sprintf(
            'contains all of "%s"',
            $strings->join(', ')
        );
    }

    protected function matches($other): bool {
        if ([] === $this->strings) {
            return true;
        }

        return 0 === collect($this->strings)->filter(function ($string) use ($other) {
            return $this->ignoreCase ? false === \mb_stripos($other, $string) : false === \mb_strpos($other, $string);
        })->count();
    }

    protected function failureDescription($other): string {
        $notFound = collect($this->strings)->filter(function ($string) use ($other) {
            return $this->ignoreCase ? false === \mb_stripos($other, $string) : false === \mb_strpos($other, $string);
        })->join(',');

        return "the following strings [{$notFound}]\n
        of all strings [".collect($this->strings)->join(',')."]\n
        were not found in:\n
        {$other}";
    }
}
