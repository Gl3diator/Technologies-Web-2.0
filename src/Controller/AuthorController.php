<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author/{name}', name: 'app_author')]
    public function showAuthor(string $name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/author', name: 'app_author_default')]
    public function showDefault(): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => '', 
        ]);
    }

    #[Route(path:'/showAll', name:'showAll' )]
    public function showAll (AuthorRepository $repo) : Response{

        $author=$repo -> findAll();
        return $this-> render(view:'author/showAll.html.twig' , parameters:['list'=>$author]);
    }
}