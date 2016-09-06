<?php
/**
 * Tags controller class.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BlogBundle\Entity\Tag;
use BlogBundle\Form\TagType;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TagsController.
 *
 * @Route(service="app.tags_controller")
 * @package BlogBundle\Controller
 * @author Monika Malinowska
 */
class TagsController extends Controller
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
     * @var ObjectRepository $model
     */
    private $model;

    /**
     * Form factory.
     *
     * @var FormFactory $formFactory
     */
    private $formFactory;

    /**
     * TagsController constructor.
     *
     * @param Translator $translator Translator
     * @param EngineInterface $templating Templating engine
     * @param Session $session Session
     * @param RouterInterface $router
     * @param ObjectRepository $model Model object
     * @param FormFactory $formFactory Form factory
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        ObjectRepository $model,
        FormFactory $formFactory
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->model = $model;
        $this->formFactory = $formFactory;
    }
    /**
     * Index action.
     *
     * @Route("/tags", name="tags")
     * @Route("/tags/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function indexAction()
    {
        $tags = $this->model->findAll();
        if (!$tags) {
            throw new NotFoundHttpException(
                $this->translator->trans('tags.messages.tags_not_found')
            );
        }
        return $this->templating->renderResponse(
            'BlogBundle:Tags:index.html.twig',
            array('tags' => $tags)
        );
    }

    /**
     * View action.
     *
     * @Route("/tags/view/{id}", name="tags-view")
     * @Route("/tags/view/{id}/")
     * @ParamConverter("tag", class="BlogBundle:Tag")
     *
     * @param Tag $id
     * @return Response
     * @internal param Tag $tag Tag entity
     */
    public function viewAction(Tag $id)
    {
        $tag = $this->model->findOneById($id);
        if (!$tag) {
            throw new NotFoundHttpException(
                $this->translator->trans('tags.messages.tags_not_found')
            );
        }

        $posts = $tag->getPosts();
        
        return $this->templating->renderResponse(
            'BlogBundle:Tags:view.html.twig',
            array('tag' => $tag,
            'posts' => $posts)
        );
    }

    /**
     * Add action.
     *
     * @Route("/tags/add", name="tags-add")
     * @Route("/tags/add/")
     *
     * @param Request $request
     * @return Response A Response instance
     */
    public function addAction(Request $request)
    {
        $tagForm = $this->formFactory->create(
            new TagType(),
            null,
            array(
                'validation_groups' => 'tag-default'
            )
        );

        $tagForm->handleRequest($request);

        if ($tagForm->isValid()) {
            $tag = $tagForm->getData();
            $this->model->save($tag);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('tags.messages.success.add')
            );
            return new RedirectResponse(
                $this->router->generate('tags')
            );
        }

        return $this->templating->renderResponse(
            'BlogBundle:Tags:add.html.twig',
            array('form' => $tagForm->createView())
        );
    }

    /**
     * Edit action.
     *
     * @Route("/tags/edit/{id}", name="tags-edit")
     * @Route("/tags/edit/{id}/")
     * @ParamConverter("tag", class="BlogBundle:Tag")
     *
     * @param Request $request
     * @param Tag $tag
     * @return Response A Response instance
     */
    public function editAction(Request $request, Tag $tag)
    {
        if (!$tag) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('tags.messages.tag_not_found')
            );
            return new RedirectResponse(
                $this->router->generate('tags-add')
            );
        }

        $tagForm = $this->formFactory->create(
            new TagType(),
            $tag,
            array(
                'validation_groups' => 'tag-default'
            )
        );

        $tagForm->handleRequest($request);

        if ($tagForm->isValid()) {
            $tag = $tagForm->getData();
            $this->model->save($tag);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('tags.messages.success.edit')
            );
            return new RedirectResponse(
                $this->router->generate('tags')
            );
        }

        return $this->templating->renderResponse(
            'BlogBundle:Tags:edit.html.twig',
            array('form' => $tagForm->createView())
        );

    }

    /**
     * Delete action.
     *
     * @Route("/tags/delete/{id}", name="tags-delete")
     * @Route("/tags/delete/{id}/")
     * @ParamConverter("tag", class="BlogBundle:Tag")
     *
     * @param Request $request
     * @param Tag $tag
     * @return Response A Response instance
     */
    public function deleteAction(Request $request, Tag $tag = null)
    {
        if (!$tag) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('tags.messages.tag_not_found')
            );
            return new RedirectResponse(
                $this->router->generate('tags')
            );
        }

        $tagForm = $this->formFactory->create(
            new TagType(),
            $tag,
            array(
                'validation_groups' => 'tag-delete'
            )
        );

        $tagForm->handleRequest($request);

        if ($tagForm->isValid()) {
            $tag = $tagForm->getData();
            $this->model->delete($tag);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('tags.messages.success.delete')
            );
            return new RedirectResponse(
                $this->router->generate('tags')
            );
        }

        return $this->templating->renderResponse(
            'BlogBundle:Tags:delete.html.twig',
            array('tag'=>$tag,
                'form' => $tagForm->createView())
        );

    }
}
