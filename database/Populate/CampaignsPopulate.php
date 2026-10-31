<?php

namespace Database\Populate;

use App\Models\Campaign;
use App\Models\User;

class CampaignsPopulate
{
    public static function populate()
    {
        $user = User::findBy(['email' => 'fulano@example.com']);

        // Trava de segurança para você saber exatamente se falhou
        if (!$user) {
            die("Erro Crítico: Nenhum usuário encontrado para vincular as campanhas.\n");
        }

        $numberOfCampaigns = 100;
        for ($i = 0; $i < $numberOfCampaigns; $i++) {
            $campaign = new Campaign(['title' => 'Campaign ' . $i, 'user_id' => $user->id]);
            $campaign->save();
        }

        echo "Campaigns populated with $numberOfCampaigns registers\n";
    }
}