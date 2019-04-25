<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
// use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends FOSRestController
{
    /**
     * @Route("/article", name="article")
     * @param
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticleController.php',
        ]);
    }
    
    /**
     * Creates an Article resource
     * @Rest\Post("/articles")
     * @param Request $request
     * @return View
     */
    public function createArticle(Request $request): View
    {
        $article = new Article();
        $data = json_decode($request->getContent(), true);
        
        $article->setTitle($data['title']);
        $article->setContent($data['content']);
        try{

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            // In case our POST was a success we need to return a 201 HTTP CREATED response
            return View::create($article, Response::HTTP_CREATED);
        }
        catch(Exception $e){
            
            $responseObject = array(
                "response" => "success",
                "message" => sprintf('User %s successfully created', $user->getUsername()),
                "techMessage" => $e->getMessage()  // For admin
            );

            return new JsonResponse($responseObject);
        }
    }

    /**
     * Retrieves an Article resource
     * @Rest\Get("/articles/{id}")
     */
    public function getArticle(Article $article): View
    {
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($article, Response::HTTP_OK);
    }

    /**
     * Retrieves a collection of Article resource
     * @Rest\Get("/articles")
     */
    public function listArticles(): View
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        
        // In case our GET was a success we need to return a 200 HTTP OK response with the collection of article object
        return View::create($articles, Response::HTTP_OK);
    }

    /**
     * Replaces Article resource
     * @Rest\Put("/articles/{id}")
     */
    public function putArticle(Article $article, Request $request): View
    {        
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $myArticle = $repository->find($article->getId());

        if(is_null($myArticle)){
            throw new NotFoundHttpException("Article non trouver dans la base");
        }

        $data = json_decode($request->getContent(), true);
        
        $myArticle->setTitle($data['title']);
        $myArticle->setContent($data['content']);

        $em->persist($myArticle);
        $em->flush();
        // In case our PUT was a success we need to return a 200 HTTP OK response with the object as a result of PUT
        return View::create($myArticle, Response::HTTP_OK);
    }

    /**
     * Removes the Article resource
     * @Rest\Delete("/articles/{id}")
     */
    public function deleteArticle(Article $article): View
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($article);
        $em->flush();
        // In case our DELETE was a success we need to return a 204 HTTP NO CONTENT response. The object is deleted.
        return View::create([], Response::HTTP_NO_CONTENT);
    }
}
