<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $subscriber;

    /**
     * Create a new message instance.
     *
     * @param mixed $campaign
     * @param mixed $subscriber
     * @return void
     */
    public function __construct($campaign, $subscriber)
    {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->campaign->subject)
                    ->view('emails.campaign');
    }
}
