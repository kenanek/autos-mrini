<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    protected $fillable = ['campaign_id', 'subscriber_id', 'status', 'error', 'sent_at'];
    protected $casts = ['sent_at' => 'datetime'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
