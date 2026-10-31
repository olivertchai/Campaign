<?php

namespace Tests\Acceptance;

use App\Models\Campaign;
use App\Models\User;
use Tests\Support\AcceptanceTester;

class CampaignCest extends BaseAcceptanceCest
{
    // O menu do layout também tem um link "Criar Campanha", então clicar pelo texto
    // navegaria em vez de enviar o formulário. Por isso o botão é localizado por CSS.
    private const SUBMIT_BUTTON = 'form button[type="submit"]';
    private function createUser(): User
    {
        $user = new User([
            'name' => 'Fulano Teste',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'manager_marketing',
            'active' => true,
        ]);
        $user->save();

        return $user;
    }

    private function login(AcceptanceTester $page): void
    {
        $page->amOnPage('/login');
        $page->fillField('user[email]', 'fulano@example.com');
        $page->fillField('user[password]', '123456');
        $page->click('Entrar');
    }

    private function createCampaign(User $user, string $title): Campaign
    {
        $campaign = new Campaign([
            'title' => $title,
            'description' => 'Descricao de teste',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
            'user_id' => $user->id,
        ]);
        $campaign->save();

        return $campaign;
    }

    // Em input type="date" o sendKeys depende do locale do navegador.
    // Setar o value em formato ISO via JS é determinístico.
    private function setValue(AcceptanceTester $page, string $field, string $value): void
    {
        $page->executeJS("document.querySelector('[name=\"{$field}\"]').value = '{$value}';");
    }

    // 1.1 Tentativa de cadastro com dados incorretos
    public function tryToCreateCampaignWithInvalidData(AcceptanceTester $page): void
    {
        $this->createUser();
        $this->login($page);

        $page->amOnPage('/pages/campaign/new');
        $page->click(self::SUBMIT_BUTTON);

        $page->see('Existem dados incorretos! Por favor, verifique!');
        $page->see('não pode ser vazio!');
        $page->seeCurrentUrlEquals('/pages/campaign');
    }

    // 1.2 Tentativa de cadastro bem-sucedida
    public function tryToCreateCampaignSuccessfully(AcceptanceTester $page): void
    {
        $this->createUser();
        $this->login($page);

        $page->amOnPage('/pages/campaign/new');
        $page->fillField('campaign[title]', 'Campanha de Natal');
        $page->fillField('campaign[description]', 'Descricao da campanha de Natal');
        $this->setValue($page, 'campaign[start_date]', '2026-12-01');
        $this->setValue($page, 'campaign[end_date]', '2026-12-25');
        $page->click(self::SUBMIT_BUTTON);

        $page->seeCurrentUrlEquals('/campaigns');
        $page->see('Campanha registrada com sucesso!');
        $page->see('Campanha de Natal');
    }

    // 1.3 Tentativa de atualização com dados incorretos
    public function tryToUpdateCampaignWithInvalidData(AcceptanceTester $page): void
    {
        $user = $this->createUser();
        $campaign = $this->createCampaign($user, 'Campanha Original');
        $this->login($page);

        $page->amOnPage("/campaigns/{$campaign->id}/edit");
        $this->setValue($page, 'campaign[title]', '');
        $page->click(self::SUBMIT_BUTTON);

        $page->see('Existem dados incorretos! Por favor, verifique!');
        $page->see('não pode ser vazio!');
        $page->seeCurrentUrlEquals("/campaigns/{$campaign->id}");
    }

    // 1.4 Tentativa de atualização bem-sucedida
    public function tryToUpdateCampaignSuccessfully(AcceptanceTester $page): void
    {
        $user = $this->createUser();
        $campaign = $this->createCampaign($user, 'Campanha Original');
        $this->login($page);

        $page->amOnPage("/campaigns/{$campaign->id}/edit");
        $page->fillField('campaign[title]', 'Campanha Atualizada');
        $page->click(self::SUBMIT_BUTTON);

        $page->seeCurrentUrlEquals('/campaigns');
        $page->see('Campanha atualizada com sucesso!');
        $page->see('Campanha Atualizada');
        $page->dontSee('Campanha Original');
    }

    // 1.5 Listagem de todos os registros
    public function tryToListAllCampaigns(AcceptanceTester $page): void
    {
        $user = $this->createUser();
        $this->createCampaign($user, 'Campanha Alfa');
        $this->createCampaign($user, 'Campanha Beta');
        $this->createCampaign($user, 'Campanha Gama');
        $this->login($page);

        $page->amOnPage('/campaigns');

        $page->see('Campanha Alfa');
        $page->see('Campanha Beta');
        $page->see('Campanha Gama');
    }

    // 1.6 Remoção do registro
    public function tryToRemoveCampaign(AcceptanceTester $page): void
    {
        $user = $this->createUser();
        $campaign = $this->createCampaign($user, 'Campanha Para Remover');
        $this->login($page);

        $page->amOnPage("/campaigns/{$campaign->id}");
        $page->click('Excluir');

        $page->seeCurrentUrlEquals('/campaigns');
        $page->see('Campanha removida com sucesso!');
        $page->dontSee('Campanha Para Remover');
    }

    // 1.7 Paginação dos registros (10 por página)
    public function tryToPaginateCampaigns(AcceptanceTester $page): void
    {
        $user = $this->createUser();
        for ($i = 1; $i <= 11; $i++) {
            $this->createCampaign($user, sprintf('Campanha %02d', $i));
        }
        $this->login($page);

        $page->amOnPage('/campaigns');
        $page->see('Campanha 01');
        $page->see('Campanha 10');
        $page->dontSee('Campanha 11');

        $page->click('Próxima');

        $page->seeCurrentUrlEquals('/campaigns/page/2');
        $page->see('Campanha 11');
        $page->dontSee('Campanha 01');
    }
}
