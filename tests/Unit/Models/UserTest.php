<?php

namespace Tests\Unit\Models;

use App\Models\Lesson;
use App\Models\User;
use Mockery;
use PHPUnit\Framework\TestCase;
// ユニットテストでフレームワークの機能を使えるようにする場合は継承元のクラスを変更する
// use Tests\TestCase

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
        /** @var \Mockery\MockInterface $userMockery */
        $userMockery = Mockery::mock(User::class);
        /** @var User|\Mockery\MockInterface $user */
        $user = $userMockery->makePartial();
        $user->shouldReceive('reservationCountThisMonth')->andReturn($reservationCount);
        $user->plan = $plan;

        /** @var Lesson $lesson */
        $lesson = Mockery::mock(Lesson::class);
        $lesson->shouldReceive('remainingCount')->andReturn($remainingCount);

        $this->assertSame($canReserve, $user->canReserve($lesson, $reservationCount));
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
