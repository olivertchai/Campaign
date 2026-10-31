<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsToMany;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Core\Database\Database;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 * @property string $role
 * @property bool $active
 * @property Campaign[] $campaigns
 * @property Campaign[] $reinforced_campaigns
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['name', 'email', 'encrypted_password', 'role', 'active'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'user_id');
    }

    public function reinforcedCampaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_user_reinforce', 'user_id', 'campaign_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getTotalUsers(): int
    {
        $pdo = Database::getDatabaseConn();
        $resp = $pdo->query('SELECT COUNT(*) as total FROM users');
        $row = $resp->fetch();

        return $row ? (int) $row['total'] : 0;
    }

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('email', $this);

        Validations::uniqueness('email', $this);

        if ($this->newRecord()) {
            Validations::passwordConfirmation($this);
        }
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password == null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public static function findByEmail(string $email): User | null
    {
        return User::findBy(['email' => $email]);
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    }
}
