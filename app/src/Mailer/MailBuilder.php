<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Mailer\Shared\MailInterface;
use App\Mailer\Shared\Mime\Template;
use NotFloran\MjmlBundle\Renderer\RendererInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailBuilder
{
    private ?Email $email = null;

    /**
     * @var Address[]
     */
    private array $to = [];

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private readonly RendererInterface $mjml
    ) {}

    public function init(string $subject, Template $template): self
    {
        $templateWrapper = $this->twig->load($template->getName());
        $html = $templateWrapper->render($template->getArguments() + [
            'showPlainText' => false,
        ]);

        if ($templateWrapper->hasBlock('plain_text')) {
            $plainText = $templateWrapper->renderBlock('plain_text', $template->getArguments() + [
                'showPlainText' => true,
            ]);
        }

        $this->email = (new Email())
            ->subject($subject)
            ->html($this->mjml->render($html))
            ->text($plainText ?? null)
        ;

        return $this;
    }

    public function send(): self
    {
        if (!$this->email) {
            throw new \LogicException('Email not initialized');
        }

        $this->email->addFrom(new Address(MailInterface::SENDER_EMAIL, MailInterface::SENDER_NAME));

        foreach ($this->to as $address) {
            $this->email->addTo($address->getAddress());
        }

        if (\count($this->email->getTo()) > 0) {
            $this->mailer->send($this->email);
        }

        return $this;
    }

    /**
     * @param Address[] $addresses
     */
    public function setTo(array $addresses): self
    {
        $this->to = $addresses;

        return $this;
    }
}
