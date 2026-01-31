<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $searchTerm = $request->query->get('q');
        $page = $request->query->getInt('page', 1);
        $limit = 6;

        if ($searchTerm) {
            $articles = $articleRepository->findBySearch($searchTerm);
            $totalArticles = count($articles);
        } else {
            $criteria = ['published' => true];
            $articles = $articleRepository->findPaginated($page, $limit, $criteria);
            $totalArticles = $articleRepository->countArticles($criteria);
        }

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'searchTerm' => $searchTerm,
            'currentPage' => $page,
            'totalPages' => ceil($totalArticles / $limit),
        ]);
    }

    #[Route('/admin', name: 'article_admin', methods: ['GET'])]
    public function admin(ArticleRepository $articleRepository): Response
    {
        // Vue d'administration : tous les articles (publiés et brouillons)
        $articles = $articleRepository->findAll();

        return $this->render('article/admin.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/historique', name: 'article_history', methods: ['GET'])]
    public function history(ArticleRepository $articleRepository): Response
    {
        // Historique : uniquement les articles dépubliés (brouillons)
        $articles = $articleRepository->findBy(['published' => false]);

        return $this->render('article/history.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article, EntityManagerInterface $entityManager): Response
    {
        // On incrémente le compteur de vues au niveau Back-end
        $article->incrementViews();
        $entityManager->flush();

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/toggle', name: 'article_toggle', methods: ['POST'])]
    public function toggle(Article $article, EntityManagerInterface $entityManager): Response
    {
        // Inverse l'état publié/dépublié
        $article->setPublished(!$article->isPublished());

        // Sauvegarde en base
        $entityManager->flush();

        // Retour à la liste des articles
        return $this->redirectToRoute('article_index');
    }

    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        // On remplace le simple toggle par une suppression réelle
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
}
