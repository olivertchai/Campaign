<?php

namespace Tests\Acceptance\home;

use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class HomeIndexCest extends BaseAcceptanceCest
{
    public function seeHomePageWhenAuthenticated(AcceptanceTester $page): void
    {
        $user = new \App\Models\User([
            'name'                  => 'Fulano Teste',
            'email'                 => 'fulano@example.com',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ]);
        $user->save();

        $page->amOnPage('/login');
        $page->fillField('user[email]', 'fulano@example.com');
        $page->fillField('user[password]', '123456');
        $page->click('Entrar');

        $page->amOnPage('/');
        $page->see('Campanhas de Marketing', '//h1');
    }
}
