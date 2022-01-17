<?php

namespace Tests\Unit\Models;

use App\Models\VacancyLevel;
use PHPUnit\Framework\TestCase;

class VacancyLevelTest extends TestCase
{
    /**
     * @param integer $remainingCount
     * @param string $expectedMark
     * @dataProvider dataMark
     */
    public function testMark(int $remainingCount, string $expectedMark)
    {
        $level = new VacancyLevel($remainingCount);
        $this->assertSame($expectedMark, $level->mark());
    }

    public function dataMark()
    {
        return [
            '空き無し' => [
                'remainingCount' => 0,
                'expectedMark' => '×'
            ],
            '残りわずか' => [
                'remainingCount' => 4,
                'expectedMark' => '△'
            ],
            '空き十分' => [
                'remainingCount' => 5,
                'expectedMark' => '◎'
            ],
        ];
    }

    /**
     * @param integer $remainingCount
     * @param string $expectedSlug
     * @dataProvider dataSlug
     */
    public function testSlug(int $remainingCount, string $expectedSlug)
    {
        $level = new VacancyLevel($remainingCount);
        $this->assertSame($expectedSlug, $level->slug());
    }

    public function dataSlug()
    {
        return [
            '空き無し' => [
                'remainingCount' => 0,
                'expectedSlug' => 'empty'
            ],
            '残りわずか' => [
                'remainingCount' => 4,
                'expectedSlug' => 'few'
            ],
            '空き十分' => [
                'remainingCount' => 5,
                'expectedSlug' => 'enough'
            ],
        ];
    }
}
