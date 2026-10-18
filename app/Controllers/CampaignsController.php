<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class CampaignsController extends Controller
{
    protected string $layout = 'application';

    public function index(): void
    {
        $title = 'Campanhas de Marketing';
        $this->render('campaign/index', compact('title'));
    }
}