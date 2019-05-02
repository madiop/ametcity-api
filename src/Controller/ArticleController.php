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
// use Nelmio\ApiDocBundle\Annotation as Doc;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

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
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of Article resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Article::class, groups={"full"}))
     *     )
     * )
     *     @SWG\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Sort criterion",
     *         type="string",
     *     )
     *     @SWG\Parameter(
     *         name="order",
     *         in="query",
     *         description="Order criterion",
     *         type="string",
     *     )
     *     @SWG\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Page number",
     *         type="integer",
     *     )
     *     @SWG\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Page number",
     *         type="integer",
     *     )
     * @SWG\Tag(name="articles")
     * @Security(name="Bearer")
     *
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
     * @Rest\Get(
     *     path="/articles/{id}",
     *     name="app_article_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false)
     */
    public function getArticle(Article $article) : Article
    {
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
     * @Rest\Put(
     *     path="/articles/{id}",
     *     name="app_article_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newArticle", class="App\Entity\Article", converter="fos_rest.request_body")
     */
    public function putArticle(Article $article, Article $newArticle, ConstraintViolationList $violations): Article
    {
        $this->dataValidator->validate($violations);

        $article->setTitle($newArticle->getTitle());
        $article->setContent($newArticle->getContent());

        $this->em->persist($article);
        $this->em->flush();
        
        return $article;
    }
    // * @ParamConverter("article", class="App\Entity\Article", converter="fos_rest.request_body")

    /**
     * Removes the Article resource
     * @Rest\Delete(
     *     path="/articles/{id}",
     *     name="app_article_delete"
     * )
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
