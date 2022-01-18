<?php

namespace Tests\Factories\Traits;

use App\Models\User;
use App\Models\UserProfile;

trait CreatesUser
{
  /**
   * UserとUserProfileをセットで作成
   *
   * @return User
   */
  private function createUser(): User
  {
    /** @var User $user */
    $user = factory(User::class)->create();
    $user->profile()->save(factory(UserProfile::class)->make());

    return $user;
  }
}
