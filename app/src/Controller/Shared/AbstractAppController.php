<?php

declare(strict_types=1);

namespace App\Controller\Shared;

use App\Form\Handler\Shared\FormHandlerManager;
use App\Pager\PagerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractAppController extends AbstractController
{
    final public const FLASH_SUCCESS = 'success';

    #[Required]
    public FormHandlerManager $formHandlerManager;
    #[Required]
    public TranslatorInterface $translator;
    #[Required]
    public PagerManager $pagerManager;
}
