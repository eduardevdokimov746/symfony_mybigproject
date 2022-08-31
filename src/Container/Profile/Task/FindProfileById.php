<?php

namespace App\Container\Profile\Task;

use App\Container\Profile\Data\Repository\ProfileRepository;
use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Exception\ProfileNotFoundException;
use App\Ship\Parent\Task;

class FindProfileById extends Task
{
    public function __construct(
        private ProfileRepository $profileRepository
    )
    {
    }

    public function run(int $id): Profile
    {
        if (is_null($profile = $this->profileRepository->find($id)))
            throw new ProfileNotFoundException();

        return $profile;
    }
}