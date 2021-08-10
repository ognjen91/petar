<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Excursion;
use Carbon\Carbon;

class ExcursionAlmostFull extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $excursion;

    public function __construct(Excursion $excursion)
    {
        $this->excursion = $excursion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.admin.excursion-almost-full')->subject('Upozorenje: ' . Carbon::now()->format('d.m.Y H:i'));
    }
}
