<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/articles')]
class ArticleController extends AbstractController
{
    #[Route('', name: 'article_index')]
    public function index(ArticleRepository $repo): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'article_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setPublishedAt(new \DateTime());
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', name: 'article_edit')]
    public function edit(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());
            $em->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', ['form' => $form, 'article' => $article]);
    }

    #[Route('/{id}/delete', name: 'article_delete', methods: ['POST'])]
    public function delete(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
