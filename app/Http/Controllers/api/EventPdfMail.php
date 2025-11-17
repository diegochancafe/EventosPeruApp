<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class EventPdfMail extends Mailable

{
  public $event;
  public $pdfContent;

  public function __construct($event, $pdfContent)
  {
    $this->event = $event;
    $this->pdfContent = $pdfContent;
  }

  public function build()
  {
    return $this->subject('Detalle de tu evento')
      ->view('emails.event')
      ->attachData($this->pdfContent, "evento_{$this->event->id}.pdf", [
        'mime' => 'application/pdf',
      ]);
  }
}
