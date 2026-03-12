<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    protected $fillable = ['email', 'name', 'token', 'status', 'subscribed_at', 'unsubscribed_at'];
    protected $casts = ['subscribed_at' => 'datetime', 'unsubscribed_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function ($s) {
            if (empty($s->token))
                $s->token = Str::random(64);
            if (!$s->subscribed_at)
                $s->subscribed_at = now();
        });
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function campaignLogs()
    {
        return $this->hasMany(CampaignLog::class);
    }
}
