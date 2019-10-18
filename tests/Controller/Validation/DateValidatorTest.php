<?php

namespace BasicTablePackage\Controller\Validation;

use PHPUnit\Framework\TestCase;

class DateValidatorTest extends TestCase
{
    /**
     * @dataProvider getFormats
     */
    public function test_date_formats($date, $isValid)
    {
        $fieldName = "test";
        $dateValidator = new DateValidator($fieldName);
        $postvalues[$fieldName] = $date;
        self::assertThat($dateValidator->validate($postvalues)->isError(), $isValid ? self::isFalse() : self::isTrue());
    }

    public function getFormats()
    {
        return [
            ['1980-05-31', true],
            ['1980/05/31', true],
            ['1980.05.31', true],
            ['2030.05.31', false],
            ['31-05-1980', true],
            ['31.05.1980', true],
            ['31/05/1980', false],
            ['05/31/1980', true],
            ['05.31.1980', false],
            ['05-31-1980', false],

            ['80-05-31', true],
            ['80/05/31', false],
            ['80.05.31', false],
            ['31-05-80', false],
            ['31.05.80', true],
            ['31/05/80', false],
            ['05/31/80', true],
            ['05.31.80', false],
            ['05-31-80', false],

            ['bla', false],
            [0, false],
            [-1, false],
            [1.2, false],
            [null, true],
            ["", true],
        ];
    }

    /**
     * @dataProvider getRanges
     */
    public function test_date_ranges($date, $isValid)
    {
        $fieldName = "test";
        $dateValidator = new DateValidator($fieldName);
        $postvalues[$fieldName] = $date;
        self::assertThat($dateValidator->validate($postvalues)->isError(), $isValid ? self::isFalse() : self::isTrue());
    }

    public function getRanges()
    {
        return [
            ['01.01.1900', true],
            ['31.12.2050', true],
            ['32.12.2050', false],
            ['31.13.2050', false],
            ['31.11.2050', true],
            ['31.02.2050', true],
        ];
    }
}