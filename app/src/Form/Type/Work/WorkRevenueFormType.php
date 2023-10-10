<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Entity\Company;
use App\Entity\Setting\BroadcastChannel;
use App\Enum\Company\CompanyTypeEnum;
use App\Form\Dto\Work\WorkRevenueFormDto;
use App\Form\Type\Shared\DateType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkRevenueFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('distributor', EntityType::class, [
                'placeholder' => 'Select a channel',
                'required' => true,
                'class' => Company::class,
                'query_builder' => fn (CompanyRepository $er) => $er->getByTypeQueryBuilder(CompanyTypeEnum::DISTRIBUTOR),
                'choice_label' => 'name',
            ])
            ->add('channel', EntityType::class, [
                'placeholder' => 'Select a channel',
                'required' => true,
                'class' => BroadcastChannel::class,
                'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('bc')->orderBy('bc.name', 'ASC'),
                'choice_label' => 'name',
            ])
            ->add('startsAt', DateType::class, [
                'required' => true,
            ])
            ->add('endsAt', DateType::class, [
                'required' => true,
            ])
            ->add('revenue', NumberType::class, [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkRevenueFormDto::class,
        ]);
    }
}
