<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class CampaignsController extends Controller
{
    protected string $layout = 'application';

    public function index(Request $request): void
    {
        $paginator = $this->current_user->campaigns()->paginate(page: $request->getParam('page', 1));
        $campaigns = $paginator->registers();

        $title = 'Campanhas';

        if ($request->acceptJson()) {
            $this->renderJson('campaign/index', compact('paginator', 'campaigns', 'title'));
        } else {
            $this->render('campaign/index', compact('paginator', 'campaigns', 'title'));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $campaign = $this->current_user->campaigns()->findById($params['id']);

        $title = "Visualização da Campanha #{$campaign->id}";
        $this->render('campaign/show', compact('campaign', 'title'));
    }

    public function new(): void
    {
        $campaign = $this->current_user->campaigns()->new();

        $title = 'Nova Campanha';
        $this->render('campaign/new', compact('campaign', 'title'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        /** @var \App\Models\Campaign $campaign */
        $campaign = $this->current_user->campaigns()->new($params['campaign']);
        $campaign->campaign_image = $_FILES['campaign_image'] ?? null;


        if ($campaign->save()) {
            $campaign->image()->update($_FILES['campaign_image'] ?? []);

            FlashMessage::success('Campanha registrada com sucesso!');
            $this->redirectTo(route('campaigns.index'));
        } else {
            FlashMessage::danger('Existem dados incorretos! Por favor, verifique!');
            $title = 'Nova Campanha';
            $this->render('campaign/new', compact('campaign', 'title'));
        }
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $campaign = $this->current_user->campaigns()->findById($params['id']);

        if (!$campaign) {
            $this->redirectTo(route('campaigns.index'));
        }

        $title = "Editar Campanha #{$campaign->id}";
        $this->render('campaign/edit', compact('campaign', 'title'));
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('id');
        $params = $request->getParam('campaign');

        /** @var \App\Models\Campaign $campaign */
        $campaign = $this->current_user->campaigns()->findById($id);
        $campaign->title       = $params['title'];
        $campaign->subtitle    = $params['subtitle'];
        $campaign->description = $params['description'];
        $campaign->start_date  = $params['start_date'];
        $campaign->end_date    = $params['end_date'];
        $campaign->campaign_image = $_FILES['campaign_image'] ?? null;

        if ($campaign->save()) {
            $campaign->image()->update($_FILES['campaign_image'] ?? []);

            FlashMessage::success('Campanha atualizada com sucesso!');
            $this->redirectTo(route('campaigns.index'));
        } else {
            FlashMessage::danger('Existem dados incorretos! Por favor, verifique!');
            $title = "Editar Campanha #{$campaign->id}";
            $this->render('campaign/edit', compact('campaign', 'title'));
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $campaign = $this->current_user->campaigns()->findById($params['id']);
        $campaign->destroy();

        FlashMessage::success('Campanha removida com sucesso!');
        $this->redirectTo(route('campaigns.index'));
    }
}
