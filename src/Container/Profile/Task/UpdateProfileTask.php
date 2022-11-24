<?php

declare(strict_types=1);

namespace App\Container\Profile\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Task;

class UpdateProfileTask extends Task
{
    public function run(User|Profile $profile, callable $callback): Profile
    {
        $profile = $profile instanceof User ? $profile->getProfile() : $profile;

        $this->call($callback, $profile);

        $this->flush();

        return $profile;
    }
}
