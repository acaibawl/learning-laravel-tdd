<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Lesson;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{

    use RefreshDatabase;
    
    /**
     * @param integer $capacity
     * @param integer $reservationCount
     * @param string $expectedVacancyLevelMark
     * @dataProvider dataShow
     */
    public function testShow(int $capacity, int $reservationCount, string $expectedVacancyLevelMark)
    {
        // lessonsテーブルにテスト用のレコードを追加
        $lesson = factory(Lesson::class)->create(['name' => '楽しいヨガレッスン', 'capacity' => $capacity]);

        for ($i = 0; $i < $reservationCount; $i++) {
            // 同じユーザーが同一のレッスンに予約を入れることができないので、ループ内で毎回 User を生成
            $user = factory(User::class)->create();
            $lesson->reservations()->save(factory(Reservation::class)->make(['user_id' => $user]));
        }

        // リクエストを発行
        $response = $this->get("/lessons/{$lesson->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($lesson->name);
        $response->assertSee("空き状況: {$expectedVacancyLevelMark}");
    }

    public function dataShow()
    {
        return [
            '空き無し' => [
                'capacity' => 10,
                'reservationCount' => 10,
                'expectedVacancyLevelMark' => '×',
            ],
            '空き少し' => [
                'capacity' => 10,
                'reservationCount' => 9,
                'expectedVacancyLevelMark' => '△',
            ],
            '空き十分' => [
                'capacity' => 10,
                'reservationCount' => 5,
                'expectedVacancyLevelMark' => '◎',
            ],
        ];
    }
}
