<?php

declare(strict_types=1);

namespace App\Controller\Shared;

use App\Form\Handler\Shared\FormHandlerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractAppController extends AbstractController
{
    #[Required]
    public FormHandlerManager $formHandlerManager;

    #[Required]
    public TranslatorInterface $translator;
}
