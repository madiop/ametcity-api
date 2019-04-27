<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
// use FOS\RestBundle\View\View;
// use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Entity\Article;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends FOSRestController
{   
    private $repository;
    private $em;

    public function __construct(ArticleRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * Creates an Article resource
     * @Rest\Post(
     *         path = "/articles",
     *         name = "api_article_create"
     * )
     * @param Request $request
     * @Rest\View(populateDefaultVars=false)
     */
    public function createArticle(Request $request): Article
    {
        $article = new Article();
        $data = json_decode($request->getContent(), true);
        
        $article->setTitle($data['title']);
        $article->setContent($data['content']);
        try{

            // $em = $this->getDoctrine()->getManager();
            $this->em->persist($article);
            $this->em->flush();

            // In case our POST was a success we need to return a 201 HTTP CREATED response
            return $article;
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
     * 
     * @Rest\View(populateDefaultVars=false)
     * 
     */
    public function getArticle(Article $article) : Article
    {
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        // return View::create($article, Response::HTTP_OK);
        return $article;
    }

    // *     requirements="[a-zA-Z0-9]",
    /**
     * Retrieves a collection of Article resource
     * @Rest\Get("/articles")
     * @Rest\QueryParam(
     *     name="keyword",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of movies per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination offset"
     * )
     * @Rest\View(populateDefaultVars=false)
     */
    public function listArticles(ParamFetcherInterface $paramFetcher)//: array
    {
        $pager = $this->getDoctrine()->getRepository(Article::class)->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );
        
        return $pager->getCurrentPageResults();
    }

    /**
     * Replaces Article resource
     * @Rest\Put("/articles/{id}")
     * @Rest\View(populateDefaultVars=false)
     */
    public function putArticle(Article $article, Request $request): Article
    {        
        // $em = $this->getDoctrine()->getManager();
        // $repository = $this->getDoctrine()->getRepository(Article::class);
        $myArticle = $this->repository->find($article->getId());

        if(is_null($myArticle)){
            throw new NotFoundHttpException("Article non trouver dans la base");
        }

        $data = json_decode($request->getContent(), true);
        
        $myArticle->setTitle($data['title']);
        $myArticle->setContent($data['content']);

        $this->em->persist($myArticle);
        $this->em->flush();
        // In case our PUT was a success we need to return a 200 HTTP OK response with the object as a result of PUT
        return $myArticle;
    }

    /**
     * Removes the Article resource
     * @Rest\Delete("/articles/{id}")
     * 
     * @Rest\View(
     *      populateDefaultVars=false,
     *      StatusCode = 202
     * )
     */
    public function deleteArticle(Article $article)
    {
        // $em = $this->getDoctrine()->getManager();

        $this->em->remove($article);
        $this->em->flush();
    }
}
