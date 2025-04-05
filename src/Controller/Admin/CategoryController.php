<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/admin/category', name: 'admin.category.')]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $CatRepository): Response
    {
        $categories = $CatRepository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em)
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Nouvelle catégorie créée !');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Category $Category, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(CategoryType::class, $Category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Categorie ' . $Category->getName() . ' modifiée');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function remove(Category $categoy, EntityManagerInterface $em)
    {
        $em->remove($categoy);
        $em->flush();
        $this->addFlash('danger', 'Category ' . $categoy->getName() . ' supprimée !');
        return $this->redirectToRoute('admin.category.index');
    }
}
