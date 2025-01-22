<?php

declare(strict_types=1);

namespace App\Module\Gateway\DTO;

final class SendEmailDTO
{
    public array $emails;
    public string $subject;
    public string $content;
    public ?array $attachments;

    public function __construct(string $subject, string $content, array $emails, array $attachments = [])
    {
        $this->subject     = $subject;
        $this->content     = $content;
        $this->emails      = $emails;
        $this->attachments = $attachments;
    }
}
