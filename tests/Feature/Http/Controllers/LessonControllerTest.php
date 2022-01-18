<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Lesson;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\Factories\Traits\CreatesUser;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{

    use RefreshDatabase;
    use CreatesUser;
    
    /**
     * @param integer $capacity
     * @param integer $reservationCount
     * @param string $expectedVacancyLevelMark
     * @param string $button
     * @dataProvider dataShow
     */
    public function testShow(int $capacity, int $reservationCount, string $expectedVacancyLevelMark, string $button)
    {
        // lessonsテーブルにテスト用のレコードを追加
        $lesson = factory(Lesson::class)->create(['name' => '楽しいヨガレッスン', 'capacity' => $capacity]);

        for ($i = 0; $i < $reservationCount; $i++) {
            // 同じユーザーが同一のレッスンに予約を入れることができないので、ループ内で毎回 User を生成
            $user = factory(User::class)->create();
            factory(UserProfile::class)->create(['user_id' => $user->id]);
            $lesson->reservations()->save(factory(Reservation::class)->make(['user_id' => $user]));
        }

        // ログイン状態にする
        $user = $this->createUser();
        $this->actingAs($user);

        // リクエストを発行
        $response = $this->get("/lessons/{$lesson->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($lesson->name);
        $response->assertSee("空き状況: {$expectedVacancyLevelMark}");
        $response->assertSee($button, false);
    }

    public function dataShow()
    {
        $button = '<button class="btn btn-primary">このレッスンを予約する</button>';
        $span = '<span class="btn btn-primary disabled">予約できません</span>';
        
        return [
            '空き無し' => [
                'capacity' => 10,
                'reservationCount' => 10,
                'expectedVacancyLevelMark' => '×',
                'button' => $span,
            ],
            '空き少し' => [
                'capacity' => 10,
                'reservationCount' => 9,
                'expectedVacancyLevelMark' => '△',
                'button' => $button,
            ],
            '空き十分' => [
                'capacity' => 10,
                'reservationCount' => 5,
                'expectedVacancyLevelMark' => '◎',
                'button' => $button,
            ],
        ];
    }
}
