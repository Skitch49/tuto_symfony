<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('slug', TextType::class, ['required' => false])
            ->add('duration', TextType::class, ['label' => 'Durée'])
            ->add('category',EntityType::class,['class'=> Category::class, 'choice_label'=> 'name','expanded'=>true,'label' => 'Categorie'])
            ->add('description', TextareaType::class, ['empty_data' => ''])// Si null on laisse une chaine de caratere vide.
            

            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimestamps(...))
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
        if (!($data instanceof Recipe)) {
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
            'data_class' => Recipe::class,
        ]);
    }
}
