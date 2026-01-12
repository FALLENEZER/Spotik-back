<?php

namespace App\Controller;

use App\Entity\User;
use Override;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AppController extends AbstractController
{
    #[Override]
    protected function getUser(): ?User
    {
        $user = parent::getUser();
        if ($user instanceof User) {
            return $user;
        }

        return null;
    }
}
