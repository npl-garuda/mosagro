<?php
namespace App\Mail;
use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
class DealerRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public Lead $lead)
    {
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Заявка стать дилером - '.$this->lead->name,
        );
    }
    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer-request',
        );
    }
}
