<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Validators\Validator;

class ArticleController extends GenericController
{   
    private $repository;

    public function __construct(EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $em->getRepository(Article::class);
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
     * @SWG\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="Sort criterion",
     *     type="string",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     description="Order criterion",
     *     type="string",
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Page number",
     *     type="integer",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="Page number",
     *     type="integer",
     * )
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
        $pager = $this->repository->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return [
            "totalItems" => $pager->getNbResults(),
            "currentPage" => $pager->getCurrentPage(),
            "items" => $pager->getCurrentPageResults()
        ];
    }

    /**
     * Retrieves an Article resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves an Article resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Article::class, groups={"full"}))
     *     )
     * )
     * @SWG\Tag(name="articles")
     * @Security(name="Bearer")
     * 
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
     * @SWG\Response(
     *     response=200,
     *     description="Create an Article resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Article::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="article",
     *     in="body",
     *     @Model(type=Article::class, groups={"full"})
     * )
     * @SWG\Tag(name="articles")
     * @Security(name="Bearer")
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
     * @SWG\Response(
     *     response=200,
     *     description="The updated Article resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Article::class, groups={"full"}))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newArticle",
     *     in="body",
     *     @Model(type=Article::class, groups={"full"})
     * )
     * @SWG\Tag(name="articles")
     * @Security(name="Bearer")
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
     * Removes an Article resource
     * @SWG\Response(
     *     response=202,
     *     description="Removes the Article resource"
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Tag(name="articles")
     * @Security(name="Bearer")
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
