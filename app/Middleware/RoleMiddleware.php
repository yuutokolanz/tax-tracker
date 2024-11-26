<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class RoleMiddleware implements Middleware
{
    private int $requiredRoleLevel;

    public function __construct(int $requiredRoleLevel)
    {
        $this->requiredRoleLevel = $requiredRoleLevel;
    }

    public function handle(Request $request): void
    {
        $accountant = Auth::user();
        if ($accountant === null || $accountant->role_id < $this->requiredRoleLevel) {
            FlashMessage::danger('Você não tem permissão para acessar essa página!');
            header('Location: ' . route('index'));
            exit;
        }
    }

    public static function withRoleLevel(int $requiredRoleLevel): self
    {
        return new self($requiredRoleLevel);
    }
}
