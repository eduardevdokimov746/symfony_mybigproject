<?php

declare(strict_types=1);

namespace App\Container\Profile\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Task;

class CreateProfileTask extends Task
{
    public function run(User $user, callable $callback = null): Profile
    {
        $profile = new Profile($user);

        $this->call($callback, $profile);

        $this->persistAndFlush($profile);

        return $profile;
    }
}
