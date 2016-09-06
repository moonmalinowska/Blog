<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 12.05.16
 * Time: 22:20
 *
 * Posts Controller Class
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Controller;

use BlogBundle\Form\CommentType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Entity\Post;
use BlogBundle\Entity\Comment;
use BlogBundle\Controller\CommentsController;
use BlogBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use BlogBundle\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\SecurityContext;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

/**
 * Class PostsController.
 *
 * @Route(service="app.posts_controller")
 *
 * @package BlogBundle\Controller
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 * @author Monika Malinowska
 * @copyright (c) 2016 Monika Malinowska
 */
class PostsController extends Controller
{
    /**
     * Translator object.
     *
     * @var Translator $translator
     */
    private $translator;
    /**
     * Template engine.
     *
     * @var EngineInterface $templating
     */
    private $templating;

    /**
     * Session object.
     *
     * @var Session $session
     */
    private $session;

    /**
     * Routing object.
     *
     * @var RouterInterface $router
     */
    private $router;

    
    /**
     * Tags model object.
     *
     * @var ObjectRepository $tagsModel
     */
    private $tagsModel;
    /**
     * Posts model object.
     *
     * @var ObjectRepository $postsModel
     */
    private $postsModel;

    
    /**
     * Comments model object.
     *
     * @var ObjectRepository $commentsModel
     */
    private $commentsModel;

    /**
     * Image uploader.
     *
     * @var $imageUploader
     */
    private $imageUploader;

    /**
     * Form factory.
     *
     * @var FormFactory $formFactory
     */
    private $formFactory;

    /**
     * SecurityContext object.
     *
     * @var SecurityContext $securityContext
     */
    private $securityContext;
    /**
     * Entity manager object.
     *
     * @var EntityManager $entityManager
     */
    private $entityManager;
    /**
     * Knp_paginator object.
     *
     * @var  $knp_paginator
     */
    private $knp_paginator;

    /**
     * PostsController constructor.
     *
     * @param Translator $translator Translator
     * @param EngineInterface $templating Templating engine
     * @param Session $session Session
     * @param RouterInterface $router
     * @param ObjectRepository $tagsModel Tags model
     * @param ObjectRepository $postsModel Posts model
     * @param ObjectRepository $commentsModel Comments model
     * @param $imageUploader
     * @param FormFactory $formFactory Form factory
     * @param SecurityContext $securityContext
     * @param EntityManager $entityManager
     * @param $knp_paginator
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        ObjectRepository $tagsModel,
        ObjectRepository $postsModel,
        ObjectRepository $commentsModel,
        $imageUploader,
        FormFactory $formFactory,
        SecurityContext $securityContext,
        EntityManager $entityManager,
        $knp_paginator
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->tagsModel = $tagsModel;
        $this->postsModel = $postsModel;
        $this->commentsModel = $commentsModel;
        $this->imageUploader = $imageUploader;
        $this->formFactory = $formFactory;
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
        $this->knp_paginator = $knp_paginator;
    }

    /**
     * Index action.
     *
     * @Route("/posts/{page}", defaults={"page": 1}, requirements={"page": "\d+" }, name="posts")
     * @Route("/posts/{page}/", defaults={"page": 1}, requirements={"page": "\d+" }, name="posts")
     * @Route("/posts")
     * @Route("/")
     *
     * @param Request $request
     * @return Response
     * @internal param int $page
     *
     */
    public function indexAction(Request $request)
    {
        $tags = $this->tagsModel->findAll();
        $query    = $this->postsModel->getAllPosts();
        //$dql   = "SELECT a FROM BlogBundle:Post a ORDER BY a.created DESC ";
        //$query = $em->createQuery($dql);

        $paginator  = $this->knp_paginator;
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        //$posts = $this->postsModel->findBy(array(), array('created' => 'DESC'));
        

        if (!$pagination) {
            throw new NotFoundHttpException(
                $this->translator->trans('posts.messages.posts_not_found')
            );
        }

        return $this->templating->renderResponse(
            'BlogBundle:Posts:index.html.twig',
            array('posts' => $pagination,
                'pagination' =>$pagination,
                'tags' => $tags
            )
        );
    }

    /**
     * List action.
     *
     * @Route("/posts/list", name="posts-list")
     * @Route("/posts/list/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function listAction()
    {
       /** $user = $this->getUser();
        $userId = $user->getId(); */
        $userId = $this->getUserId();
        //$userPostId = $this->getUser();
        //var_dump($userPostId);

        //if ($userId == $userPostId){

        $posts = $this->postsModel->findBy(array('user' => $userId));

        if (!$posts) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('posts.messages.posts_not_found')
            );
            return new RedirectResponse(
                $this->router->generate('posts-add')
            );
        }
        //}

        return $this->templating->renderResponse(
            'BlogBundle:Posts:list.html.twig',
            array('posts' => $posts,
                'userId' => $userId
            )
        );
    }

    /**
     * View action.
     *
     * @Route("/posts/view/{id}", name="posts-view")
     * @Route("/posts/view/{id}/")
     * @ParamConverter("post", class="BlogBundle:Post")
     *
     * @param Post|int $id Element id
     * @return Response
     * @internal param Comments $comments
     */
    public function viewAction(Post $id)
    {
        $post = $this->postsModel->findOneById($id);
        if (!$post) {
            throw new NotFoundHttpException(
                $this->translator->trans('posts.messages.post_not_found')
            );
        }

        //$comments = $this->commentsModel->getCommentsForPost($id);
        $comments = $post->getComments();

       /* $commentForm = $this->formFactory->create(
            new CommentType(),
            null,
            array(
                'validation_groups' => 'comment-default'
            )
        );*/
//jesli błąd to przekieruj na add-comment

        return $this->templating->renderResponse(
            'BlogBundle:Posts:view.html.twig',
            array(
                'post' => $post,
                'comments' => $comments
                //'form' => $commentForm->createView()
            )
        );
    }
    /**
     * Add action.
     *
     * @Route("/posts/add", name="posts-add")
     * @Route("/posts/add/")
     *
     * @param Request $request
     * @return Response A Response instance
     */
    public function addAction(Request $request)
    {
        $userId = $this->getUserId();
        $postForm = $this->formFactory->create(
            new PostType(),
            null,
            array(
                'validation_groups' => 'post-default',
                'tag_model' => $this->tagsModel
            )
        );

        $postForm->handleRequest($request);
        
        if ($postForm->isValid()) {
            $post = $postForm->getData();
            $user= $this->securityContext->getToken()->getUser(); // get the current user
            $post->setUser($user);
           // $post -> setUser($userId);
            //$files = $request->files->get('');//$postForm->getImage());

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $files */
            //$files = $postForm->getImage();
            //$fileName = $this->imageUploader->upload($files);

            //$tag->setName($fileName);

            $this->postsModel->save($post);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('posts.messages.success.add')
            );
            return new RedirectResponse(
                $this->router->generate('posts')
            );
        }
        return $this->templating->renderResponse(
            'BlogBundle:Posts:add.html.twig',
            array('form' => $postForm->createView())
        );
    }

    /**
     * Edit action.
     *
     * @Route("/posts/edit/{id}", name="posts-edit")
     * @Route("/posts/edit/{id}/")
     * @ParamConverter("post", class="BlogBundle:Post")
     *
     * @param Post|null $post
     * @param Request $request Request
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Post $post = null)
    {
        if (!$post) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('posts.messages.post_not_found')
            );
            return new RedirectResponse(
                $this->router->generate('posts-add')
            );
        }

        $postForm = $this->formFactory->create(
            new PostType(),
            $post,
            array(
                'validation_groups' => 'post-default',
                'tag_model' => $this->tagsModel
            )
        );
        #var_dump($task);
        $postForm->handleRequest($request);

        if ($postForm->isValid()) {
            $post = $postForm->getData();
            $user= $this->securityContext->getToken()->getUser(); // get the current user
            $post->setUser($user);
            $this->postsModel->save($post);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('posts.messages.success.edit')
            );
            return new RedirectResponse(
                $this->router->generate('posts')
            );
        }

        return $this->templating->renderResponse(
            'BlogBundle:Posts:edit.html.twig',
            array('form' => $postForm->createView())
        );

    }

    /**
     * Delete action.
     *
     * @Route("/posts/delete/{id}", name="posts-delete")
     * @Route("/posts/delete/{id}/")
     * @ParamConverter("post", class="BlogBundle:Post")
     *
     * @param Post|null $post
     * @param Request $request Request
     * @return RedirectResponse|Response
     */
    public function deleteAction(Request $request, Post $post = null)
    {
        if (!$post) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('posts.messages.post_not_found')
            );
            return new RedirectResponse(
                $this->router->generate('posts')
            );
        }

        $postForm = $this->formFactory->create(
            new PostType(),
            $post,
            array(
                'validation_groups' => 'post-delete',
                'tag_model' => $this->tagsModel
            )
        );

        $postForm->handleRequest($request);

        if ($postForm->isValid()) {
            $post = $postForm->getData();
            $comments = $post->getComments();
            $image = $post->getImageName();
            $this->postsModel->delete($post, $comments, $image);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('posts.messages.success.delete')
            );
            return new RedirectResponse(
                $this->router->generate('posts')
            );
        }

        return $this->templating->renderResponse(
            'BlogBundle:Posts:delete.html.twig',
            array('post'=>$post,
                'form' => $postForm->createView())
        );

    }

    /**
     * Get user id.
     *
     * @return int
     */
    private function getUserId()
    {
        return $this->securityContext->getToken()->getUser()->getId();
    }


    /**
     * Gets paginated posts data.
     *
     * @param integer $page Page number
     * @param integer $limit Number of records on single page
     * @return array Paginated data
     */
    public function getPaginatedData($page, $limit)
    {
        $paginator = array();
        $paginator['data'] = $this->findAllLimited($page, $limit);
        return $paginator;
    }
}
