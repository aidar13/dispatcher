<?php

declare(strict_types=1);

namespace App\Module\Notification\DTO;

final class EmailNotificationDTO
{
    public array $emails;
    public string $subject;
    public string $content;
    public ?array $attachments = [];

    public function setEmails(array $emails): void
    {
        $this->emails = $emails;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setAttachments(?array $attachments): void
    {
        $this->attachments = $attachments;
    }
}
