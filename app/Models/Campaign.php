<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['subject', 'preview_text', 'body', 'status', 'total_recipients', 'sent_count', 'failed_count', 'sent_at'];
    protected $casts = ['sent_at' => 'datetime'];

    public function logs()
    {
        return $this->hasMany(CampaignLog::class);
    }

    public function scopeDraft($q)
    {
        return $q->where('status', 'draft');
    }

    public function getProgressAttribute(): int
    {
        if ($this->total_recipients === 0)
            return 0;
        return (int)round(($this->sent_count + $this->failed_count) / $this->total_recipients * 100);
    }
}
