<?php

namespace App\Form;

use App\Entity\Category;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TypeTextType::class,['label'=>'Titre'])
            ->add('slug')
            ->add('save',SubmitType::class,['label'=>'Sauvegarder'])
            ->addEventListener(FormEvents::PRE_SUBMIT,$this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimestamps(...))
            ;
        ;
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if (empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['title']));
            $event->setData($data);
        }
    }
    
    public function attachTimestamps(PostSubmitEvent $event): void
    {

        $data = $event->getData();
        if (!($data instanceof Category)) {
            return;
        }

        $data->setUpdatedAt(new DateTimeImmutable());
        // Si il n'y a pas d'id la recette n'est pas encore créer
        if (!$data->getId()) {
            $data->setCreatedAt(new DateTimeImmutable());
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
