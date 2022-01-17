<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @param string $plan
     * @param integer $remainingCount
     * @param integer $reservationCount
     * @param boolean $canReserve
     * @dataProvider dataCanReserve
     */
    public function testCanReserve(string $plan, int $remainingCount, int $reservationCount, bool $canReserve)
    {
        /** @var \App\Models\User */
        $user = new User();
        $user->plan = $plan;

        $this->assertSame($canReserve, $user->canReserve($remainingCount, $reservationCount));
    }

    public function dataCanReserve()
    {
        return [
            '予約可：レギュラー,空きあり,月の上限以下' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 4,
                'canReserve' => true,
            ],
            '予約不可：レギュラー,空きあり,月の上限' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 5,
                'canReserve' => false,
            ],
            '予約不可：レギュラー,空きなし' => [
                'plan' => 'regular',
                'remainingCount' => 0,
                'reservationCount' => 0,
                'canReserve' => false,
            ],
            '予約不可：ゴールド,空きあり' => [
                'plan' => 'gold',
                'remainingCount' => 1,
                'reservationCount' => 5,
                'canReserve' => true,
            ],
            '予約不可：ゴールド,空きなし' => [
                'plan' => 'gold',
                'remainingCount' => 0,
                'reservationCount' => 0,
                'canReserve' => false,
            ],
        ];
    }
}
