<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Lib\FlashMessage;

class UsersController extends Controller
{
    protected string $layout = 'application';

    public function index(): void
    {
        // 2. Trava de Segurança: Se não for admin, manda de volta pras campanhas
        if (!$this->currentUser() || !$this->currentUser()->isAdmin()) {
            FlashMessage::danger('Você não pode acessar esta página!');
            $this->redirectTo('/campaigns');
        }

        $users = User::all();
        $userModel = new User();
        $totalUsers = $userModel->getTotalUsers();
        $title = 'Gerenciar Usuários';

        $this->render('/user/index', compact('users', 'title', 'totalUsers'));
    }
}
