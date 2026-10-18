<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class AuthenticationCest extends BaseAcceptanceCest
{
    private function createUser(AcceptanceTester $page): void
    {
        $user = new \App\Models\User([
            'name'                  => 'Fulano Teste',
            'email'                 => 'fulano@example.com',
            'password'              => '123456',
            'password_confirmation' => '123456',
            'role'                  => 'manager_marketing',
            'active'                => true
        ]);
        $user->save();
    }

    // 1.1 Tentativa de acesso à área restrita sem autenticação
    public function tryToAccessRestrictedAreaWithoutAuth(AcceptanceTester $page): void
    {
        $page->amOnPage('/campaigns');
        $page->seeCurrentUrlEquals('/login');
    }

    // 1.2 Tentativa de autenticação com dados incorretos
    public function tryToLoginWithWrongCredentials(AcceptanceTester $page): void
    {
        $this->createUser($page);

        $page->amOnPage('/login');
        $page->fillField('user[email]', 'fulano@example.com');
        $page->fillField('user[password]', 'senha_errada');
        $page->click('Entrar');

        $page->seeCurrentUrlEquals('/login');
        $page->see('Email e/ou senha inválidos!');
    }

    // 1.3 Autenticação bem-sucedida
    public function tryToLoginSuccessfully(AcceptanceTester $page): void
    {
        $this->createUser($page);

        $page->amOnPage('/login');
        $page->fillField('user[email]', 'fulano@example.com');
        $page->fillField('user[password]', '123456');
        $page->click('Entrar');

        $page->seeCurrentUrlEquals('/campaigns');
        $page->see('Login realizado com sucesso!');
    }

    // 1.4 Logout
    public function tryToLogout(AcceptanceTester $page): void
    {
        $this->createUser($page);

        $page->amOnPage('/login');
        $page->fillField('user[email]', 'fulano@example.com');
        $page->fillField('user[password]', '123456');
        $page->click('Entrar');

        $page->amOnPage('/logout');
        $page->seeCurrentUrlEquals('/login');
        $page->see('Logout realizado com sucesso!');
    }
}
