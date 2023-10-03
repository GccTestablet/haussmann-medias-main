<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Mailer\Shared\MailInterface;

class MailSender
{
    public function __construct(
        private readonly MailBuilder $mailerBuilder
    ) {}

    public function send(MailInterface $mail): void
    {
        $this->mailerBuilder
            ->init($mail->subject(), $mail->template())
            ->setFrom($mail->from())
            ->setTo($mail->to())
            ->send()
        ;
    }
}
