<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Validators\Validator;

class ArticleController extends FOSRestController
{   
    private $repository;
    private $em;
    private $dataValidator;

    public function __construct(ArticleRepository $repository, EntityManagerInterface $em, Validator $validator)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->dataValidator = $validator;
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

    /**
     * Creates an Article resource
     * @Rest\Post(
     *         path = "/articles",
     *         name = "api_article_create"
     * )
     * @Rest\View(populateDefaultVars=false, StatusCode = 201)
     * @ParamConverter("article", class="App\Entity\Article", converter="fos_rest.request_body")
     */
    public function createArticle(Article $article, ConstraintViolationList $violations): Article
    {
        $this->dataValidator->validate($violations);

        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }

    /**
     * Replaces Article resource
     * @Rest\Put("/articles/{id}")
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("article", class="App\Entity\Article", converter="fos_rest.request_body")
     */
    public function putArticle(Article $article, ConstraintViolationList $violations): Article
    {
        $this->dataValidator->validate($violations);

        $myArticle = $this->repository->find($article->getId());

        if(is_null($myArticle)){
            throw new NotFoundHttpException("Article non trouver dans la base");
        }
        
        $myArticle->setTitle($article->getTitle());
        $myArticle->setContent($article->getContent());

        $this->em->persist($myArticle);
        $this->em->flush();
        
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
