<?php

namespace Tests\Integration\Access;

use Tests\TestCase;
use App\Models\User;

class AuthenticatedRoutesTest extends TestCase
{
    // 2.1 Rotas autenticadas só podem ser acessadas por usuários autenticados
    public function test_authenticated_routes_redirect_unauthenticated_users(): void
    {
        unset($_SESSION['user']);

        $protectedRoutes = [
            '/campaigns',
            '/logout',
        ];

        foreach ($protectedRoutes as $path) {
            $_SERVER['REQUEST_URI'] = $path;
            $_SERVER['REQUEST_METHOD'] = 'GET';

            ob_start();
            try {
                \Core\Router\Router::init();
            } catch (\Exception $e) {
                // captura erros esperados do dispatch
            }
            $output = ob_get_clean();

            $headers = xdebug_get_headers();
            $hasRedirect = false;
            foreach ($headers as $header) {
                if (str_contains($header, 'Location: /login')) {
                    $hasRedirect = true;
                    break;
                }
            }

            $this->assertTrue(
                $hasRedirect,
                "A rota '{$path}' deveria redirecionar para /login quando não autenticado."
            );
        }
    }

    // 2.2 Rotas públicas são acessíveis por qualquer usuário
    public function test_public_routes_are_accessible_without_auth(): void
    {
        unset($_SESSION['user']);

        $publicRoutes = ['/login'];

        foreach ($publicRoutes as $path) {
            $_SERVER['REQUEST_URI'] = $path;
            $_SERVER['REQUEST_METHOD'] = 'GET';

            $exceptionThrown = false;
            ob_start();
            try {
                \Core\Router\Router::init();
            } catch (\Exception $e) {
                $exceptionThrown = true;
            }
            ob_get_clean();

            $this->assertFalse(
                $exceptionThrown,
                "A rota pública '{$path}' não deveria lançar exceção para usuários não autenticados."
            );
        }
    }
}
