<?php

namespace Tests\Integration\Access;

use App\Controllers\CampaignsController;
use Tests\Integration\Controllers\ControllerTestCase;

class CampaignsAccessTest extends ControllerTestCase
{
    // 2.1 Rotas autenticadas não devem ser acessíveis por usuários não autenticados
    public function test_authenticated_routes_should_not_be_accessible_by_unauthenticated_users(): void
    {
        unset($_SESSION['user']);

        $controller = CampaignsController::class;

        $protectedActions = [
            ['get', 'index'],
            ['get', 'new'],
            ['post', 'create'],
            ['get', 'show', ['id' => 1]],
            ['get', 'edit', ['id' => 1]],
            ['put', 'update', ['id' => 1, 'campaign' => ['title' => 'Teste']]],
            ['post', 'destroy', ['id' => 1]],
        ];

        foreach ($protectedActions as $route) {
            $httpMethod = $route[0];
            $action = $route[1];
            $params = $route[2] ?? [];

            $output = $this->$httpMethod($action, $controller, $params);

            $this->assertStringContainsString(
                'Location: /login',
                $output,
                "A action '{$action}' falhou em bloquear o acesso de visitante."
            );
        }
    }
}
