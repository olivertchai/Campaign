<?php

namespace Database\Populate;

use App\Models\Campaign;
use App\Models\User;

class CampaignsPopulate
{
    public static function populate()
    {
        $user = User::findBy(['email' => 'fulano@example.com']);

        if (!$user) {
            die("Erro Crítico: Nenhum usuário 'fulano@example.com' encontrado para vincular as campanhas.\n");
        }

        $numberOfCampaigns = 100;
        for ($i = 0; $i < $numberOfCampaigns; $i++) {
            $campaign = new Campaign([
                'title'       => 'Campaign ' . $i,
                'description' => 'Descrição de teste para a campanha ' . $i,
                'start_date'  => date('Y-m-d'),
                'end_date'    => date('Y-m-d', strtotime('+30 days')),
                'status'      => 'active',
                'user_id'     => $user->id
            ]);

            if (!$campaign->save()) {
                echo "Erro ao salvar a campanha $i:\n";
                print_r($campaign->getErrorsIndex());
            }
        }

        echo "Campaigns populated with $numberOfCampaigns registers\n";
    }
}