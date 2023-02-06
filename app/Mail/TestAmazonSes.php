<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestAmazonSes extends Mailable
{
    use Queueable, SerializesModels;

    public $body;
    public $attachments = [];
    public $options;

    /**
     * TestAmazonSes constructor.
     * @param $body
     * @param array $attachments
     * @param mixed ...$options
     */
    public function __construct($body, $attachments = [], ...$options)
    {
        $this->body = $body;
        $this->attachments = $attachments;
        $this->options = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))->view('emails.tpl');
    }
}
