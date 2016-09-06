<?php
/**
 * Comments controller class.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Controller;

use BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BlogBundle\Entity\Comment;
use BlogBundle\Form\CommentType;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CommentsController.
 *
 * @Route(service="app.comments_controller")
 * @package BlogBundle\Controller
 * @author Monika Malinowska
 */
class CommentsController extends Controller
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
     * Model object.
     *
     * @var ObjectRepository $commentsModel
     */
    private $commentsModel;

    /**
     * Model object.
     *
     * @var ObjectRepository $postsModel
     */
    private $postsModel;

    /**
     * Form factory.
     *
     * @var FormFactory $formFactory
     */
    private $formFactory;

    /**
     * CommentsController constructor.
     *
     * @param Translator $translator Translator
     * @param EngineInterface $templating Templating engine
     * @param Session $session Session
     * @param RouterInterface $router
     * @param ObjectRepository $commentsModel Model object
     * @param ObjectRepository $postsModel Model object
     * @param FormFactory $formFactory Form factory
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        ObjectRepository $commentsModel,
        ObjectRepository $postsModel,
        FormFactory $formFactory
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->commentsModel = $commentsModel;
        $this->postsModel = $postsModel;
        $this->formFactory = $formFactory;
    }


    /**
     * List action.
     *
     * @Route("/comments/list", name="comments-list")
     * @Route("/comments/list/")
     *
     * @return Response
     */
    public function listAction()
    {
        $comments = $this->commentsModel->findAll();
        if (!$comments) {
            throw new NotFoundHttpException(
                $this->translator->trans('comments.messages.comments_not_found')
            );
        }
            
        
        return $this->templating->renderResponse(
            'BlogBundle:Comments:list.html.twig',
            array('comments' => $comments
                //'form' => $commentForm->createView(),
                )
        );
    }

    /**
     * Add action.
     *
     * @Route("/comments/{id}/add", name="comments-add")
     * @Route("/comments/{id}/add/")
     * @ParamConverter("post", class="BlogBundle:Post")
     *
     * @param Post|null $post
     * @param Request $request Request
     * @return RedirectResponse|Response
     */
    public function addAction(Request $request, Post $post = null)
    {

        $id = (integer)$request->get('id', null);
        $comment = new Comment();
        $comment->setPost($post);
        $commentForm = $this->formFactory->create(
            new CommentType(),
            $comment,
            array(
                'validation_groups' => 'comment-default'
            )
        );

        $commentForm->handleRequest($request);

        if ($commentForm->isValid()) {
            $comment = $commentForm->getData();
            //$comment->setPost($post);
            $this->commentsModel->save($comment);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('comments.messages.success.add')
            );
            return new RedirectResponse(
                $this->router->generate(
                    'posts-view',
                    array('id' => $id)
                )
            );
        }

        return $this->templating->renderResponse(
            'BlogBundle:Comments:add.html.twig',
            array('form' => $commentForm->createView())
        );
    }

    /**
     * Edit action.
     *
     * @Route("/comments/edit/{id}", name="comments-edit")
     * @Route("/comments/edit/{id}/")
     * @ParamConverter("comment", class="BlogBundle:Comment")
     *
     * @param Comment|null $comment
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Comment $comment = null)
    {
        if (!$comment) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('comments.messages.tag_not_found')
            );
            return new RedirectResponse(
                $this->router->generate('comments-add')
            );
        }

        $commentForm = $this->formFactory->create(
            new CommentType(),
            $comment,
            array(
                'validation_groups' => 'comment-edit'
            )
        );

        $commentForm->handleRequest($request);

        if ($commentForm->isValid()) {
            $comment = $commentForm->getData();
            $comment->setApproved('1');
            $this->commentsModel->save($comment);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('comments.messages.success.edit')
            );
            return new RedirectResponse(
                $this->router->generate('comments-list')
            );
        }

        return $this->templating->renderResponse(
            'BlogBundle:Comments:edit.html.twig',
            array('comment'=>$comment,
                'form' => $commentForm->createView())
        );

    }

    /**
     * Delete action.
     *
     * @Route("/comments/delete/{id}", name="comments-delete")
     * @Route("/comments/delete/{id}/")
     * @ParamConverter("comment", class="BlogBundle:Comment")
     *
     * @param Comment|null $comment
     * @param Request $request Request
     * @return RedirectResponse|Response
     */
    public function deleteAction(Request $request, Comment $comment = null)
    {
        if (!$comment) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('comments.messages.comment_not_found')
            );
            return new RedirectResponse(
                $this->router->generate('comments-list')
            );
        }

        $commentForm = $this->formFactory->create(
            new CommentType(),
            $comment,
            array(
                'validation_groups' => 'comment-delete'
            )
        );

        $commentForm->handleRequest($request);

        if ($commentForm->isValid()) {
            $comment = $commentForm->getData();
            $this->commentsModel->delete($comment);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('comments.messages.success.delete')
            );
            return new RedirectResponse(
                $this->router->generate('comments-list')
            );
        }

        return $this->templating->renderResponse(
            'BlogBundle:Comments:delete.html.twig',
            array('comment'=>$comment,
                'form' => $commentForm->createView())
        );

    }
}
