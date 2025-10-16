<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    // This will display the list of published books
    #[Route('/book', name: 'app_book')]
    public function index(BookRepository $bookRepository): Response
    {
        $publishedBooks = $bookRepository->findBy(['published' => true]);
        $unpublishedBooks = $bookRepository->findBy(['published' => false]);
        
        return $this->render('book/index.html.twig', [
            'published_books' => $publishedBooks,
            'unpublished_books' => $unpublishedBooks,
        ]);
    }

    #[Route('/book/new', name: 'app_book_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $book->setPublished(true); // Set published to true by default
        
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Increment the author's book count
            $author = $book->getAuthor();
            if ($author) {
                $author->setNbBooks($author->getNbBooks() + 1);
            }
            
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Book created successfully!');
            return $this->redirectToRoute('app_book');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/book/{id}/edit', name: 'app_book_edit')]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $originalAuthor = $book->getAuthor();
        
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newAuthor = $book->getAuthor();
            
            // If author changed, update book counts
            if ($originalAuthor !== $newAuthor) {
                // Decrement old author's book count
                if ($originalAuthor) {
                    $originalAuthor->setNbBooks($originalAuthor->getNbBooks() - 1);
                }
                // Increment new author's book count
                if ($newAuthor) {
                    $newAuthor->setNbBooks($newAuthor->getNbBooks() + 1);
                }
            }
            
            $entityManager->flush();

            $this->addFlash('success', 'Book updated successfully!');
            return $this->redirectToRoute('app_book');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/book/{id}/delete', name: 'app_book_delete')]
    public function delete(Book $book, EntityManagerInterface $entityManager, AuthorRepository $authorRepository): Response
    {
        // Decrement author's book count before deletion
        $author = $book->getAuthor();
        if ($author) {
            $author->setNbBooks($author->getNbBooks() - 1);
            
            // Check if author now has zero books and delete them
            if ($author->getNbBooks() === 0) {
                $entityManager->remove($author);
            }
        }
        
        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('success', 'Book deleted successfully!');
        return $this->redirectToRoute('app_book');
    }

    #[Route('/book/{id}/show', name: 'app_book_show')]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}