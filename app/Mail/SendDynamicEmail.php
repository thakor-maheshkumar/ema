<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\EmailTemplates;

class SendDynamicEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;        
        $template = EmailTemplates::where('slug', $data['slug'])->first();
        $content = $template->parse($data);
        $template_array = $template->toArray();
        $template_array['body_content'] = $content;

        return $this->subject($template_array['subject'])->view('emails.mainmail',$template_array);
    }
}
