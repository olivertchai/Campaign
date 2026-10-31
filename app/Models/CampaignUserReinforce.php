<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $campaign_id
 * @property int $user_id
 */
class CampaignUserReinforce extends Model
{
    protected static string $table = 'campaign_user_reinforce';
    protected static array $columns = ['campaign_id', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function validates(): void
    {
        Validations::uniqueness(['campaign_id', 'user_id'], $this);
    }
}
