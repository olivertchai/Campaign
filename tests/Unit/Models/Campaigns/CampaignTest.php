<?php

namespace Tests\Unit\Models\Campaigns;

use App\Models\Campaign;
use App\Models\User;
use Lib\Paginator;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    private User $user;
    private Campaign $campaign;
    private Campaign $campaign2;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'manager_marketing',
            'active' => true,
        ]);
        $this->user->save();

        $this->campaign = new Campaign([
            'title' => 'Campaign 1',
            'description' => 'Description 1',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
            'user_id' => $this->user->id,
        ]);
        $this->campaign->save();

        $this->campaign2 = new Campaign([
            'title' => 'Campaign 2',
            'description' => 'Description 2',
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-28',
            'user_id' => $this->user->id,
        ]);
        $this->campaign2->save();
    }

    public function test_should_create_new_campaign(): void
    {
        $this->assertCount(2, Campaign::all());
    }

    public function test_all_should_return_all_campaigns(): void
    {
        $expected = [$this->campaign->id, $this->campaign2->id];

        $all = array_map(fn($campaign) => $campaign->id, Campaign::all());

        $this->assertCount(2, $all);
        $this->assertEquals($expected, $all);
    }

    public function test_destroy_should_remove_the_campaign(): void
    {
        $this->campaign->destroy();

        $this->assertCount(1, Campaign::all());
    }

    public function test_set_title(): void
    {
        $this->campaign->title = 'New title';

        $this->assertEquals('New title', $this->campaign->title);
    }

    public function test_set_description(): void
    {
        $this->campaign->description = 'New description';

        $this->assertEquals('New description', $this->campaign->description);
    }

    public function test_update_should_persist_the_changes(): void
    {
        $this->campaign->title = 'Changed title';
        $this->assertTrue($this->campaign->save());

        $this->assertEquals('Changed title', Campaign::findById($this->campaign->id)->title);
    }

    public function test_errors_should_return_errors(): void
    {
        $campaign = new Campaign();

        $this->assertFalse($campaign->isValid());
        $this->assertFalse($campaign->save());
        $this->assertTrue($campaign->hasErrors());

        $this->assertEquals('não pode ser vazio!', $campaign->errors('title'));
        $this->assertEquals('não pode ser vazio!', $campaign->errors('description'));
        $this->assertEquals('não pode ser vazio!', $campaign->errors('end_date'));
        // A validação de datas roda depois e sobrescreve o erro de start_date
        $this->assertEquals('As datas não podem ser nulas ou estarem vazias', $campaign->errors('start_date'));
    }

    public function test_errors_should_return_start_date_bigger_than_end_date_error(): void
    {
        $campaign = new Campaign([
            'title' => 'Invalid dates',
            'description' => 'Description',
            'start_date' => '2026-02-01',
            'end_date' => '2026-01-01',
            'user_id' => $this->user->id,
        ]);

        $this->assertFalse($campaign->save());
        $this->assertEquals('Data inicial não pode ser maior que a data Final', $campaign->errors('start_date'));
    }

    public function test_should_accept_same_start_and_end_date(): void
    {
        $campaign = new Campaign([
            'title' => 'One day campaign',
            'description' => 'Description',
            'start_date' => '2026-03-10',
            'end_date' => '2026-03-10',
            'user_id' => $this->user->id,
        ]);

        $this->assertTrue($campaign->save());
    }

    public function test_find_by_id_should_return_the_campaign(): void
    {
        $this->assertEquals($this->campaign->id, Campaign::findById($this->campaign->id)->id);
    }

    public function test_find_by_id_should_return_null(): void
    {
        $this->assertNull(Campaign::findById(999));
    }

    public function test_user_should_return_the_owner(): void
    {
        $this->assertEquals($this->user->id, $this->campaign->user->id);
    }

    public function test_user_campaigns_should_return_the_campaigns(): void
    {
        $this->assertCount(2, $this->user->campaigns);
    }

    public function test_paginate_should_return_a_paginator(): void
    {
        $this->assertInstanceOf(Paginator::class, Campaign::paginate());
    }
}
