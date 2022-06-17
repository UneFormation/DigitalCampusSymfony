<?php

namespace App\Controller\Trait;

Trait RoleTrait
{
    protected function checkRole(string $role)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('authentication-login');
        }
        if (!in_array($role, $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_main', ['errors' => 'not_right']);
        }
        return null;
    }
}