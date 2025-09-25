<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController
{
    #[Route('/recette/{slug}-{id}', name: 'app_recipe')]
    public function index(Request $request,string $slug,int $id): Response
    {
 dd($slug,$id);
    }
}
