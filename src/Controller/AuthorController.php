<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    // Put specific routes FIRST
    #[Route('/author/new', name: 'app_author_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        
        // Create the form
        $form = $this->createForm(AuthorType::class, $author);
        
        // Handle form submission
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the author to database
            $entityManager->persist($author);
            $entityManager->flush();
            
            // Success message
            $this->addFlash('success', 'Author created successfully!');
            
            // Redirect to authors list
            return $this->redirectToRoute('showAll');
        }
        
        // Render the form template
        return $this->render('author/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/author', name: 'app_author_default')]
    public function showDefault(): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => '', 
        ]);
    }

    // Put the parameter route LAST
    #[Route('/author/{name}', name: 'app_author')]
    public function showAuthor(string $name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route(path:'/showAll', name:'showAll' )]
    public function showAll (AuthorRepository $repo) : Response{
        $author=$repo -> findAll();
        return $this-> render(view:'author/showAll.html.twig' , parameters:['list'=>$author]);
    }


    #[Route('/author/{id}/edit', name: 'app_author_edit')]
public function edit(Request $request, Author $author, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(AuthorType::class, $author);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Author updated successfully!');
        return $this->redirectToRoute('showAll');
    }

    return $this->render('author/edit.html.twig', [
        'form' => $form->createView(),
        'author' => $author,
    ]);
}
#[Route('/author/{id}/delete', name: 'app_author_delete')]
public function delete(Author $author, EntityManagerInterface $entityManager): Response
{
    $entityManager->remove($author);
    $entityManager->flush();

    $this->addFlash('success', 'Author deleted successfully!');
    return $this->redirectToRoute('showAll');
}
}