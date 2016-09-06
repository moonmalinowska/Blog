<?php
/**
 * Users controller class.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BlogBundle\Form\UserType;
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
use FOS\UserBundle\Doctrine\UserManager;
use BlogBundle\Entity\User;
use Symfony\Component\Security\Core\SecurityContext;
use BlogBundle\Form\ChangeRoleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use FOS\UserBundle\Model\UserInterface;

/**
 * Class UsersController.
 *
 * @Route(service="app.users_controller")
 * @package BlogBundle\Controller
 * @author Monika Malinowska
 */
class UsersController extends Controller
{
    /**
     * Translator object.
     *
     * @var Translator $translator
     */
    private $translator;
    /**
     * User Manager.
     *
     * @var EngineInterface $userManager
     */
    private $userManager;
    
    /**
     * Routing object.
     *
     * @var RouterInterface $router
     */
    private $router;

    /**
     * Session object.
     *
     * @var Session $session
     */
    private $session;

    /**
     * Template engine.
     *
     * @var EngineInterface $templating
     */
    private $templating;

    /**
     * Form factory
     *
     * @var $formFactory
     */
    private $formFactory;

    /**
     * User model
     *
     * @var $userModel
     */
    private $userModel;

    /**
     * SecurityContext object.
     *
     * @var SecurityContext $securityContext
     */
    private $securityContext;

    /**
     * UsersController constructor.
     * @param EngineInterface $templating
     * @param UserManager $userManager
     * @param FormFactory $formFactory
     * @param RouterInterface $router
     * @param Translator $translator Translator
     * @param Session $session
     * @param ObjectRepository $userModel
     * @param SecurityContext $securityContext
     */
    public function __construct(
        EngineInterface $templating,
        UserManager $userManager,
        FormFactory $formFactory,
        RouterInterface $router,
        Translator $translator,
        Session $session,
        ObjectRepository $userModel,
        SecurityContext $securityContext
    ) {
        $this->templating = $templating;
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->translator = $translator;
        $this->session = $session;
        $this->userModel = $userModel;
        $this->securityContext = $securityContext;
    }

    /**
     * Index action.
     *
     * @Route("/admin/users/index/", name="users-index")
     * @Route("/admin/users/index")
     *
     * @param Request $request
     * @return Response A Response instance
     */
    public function indexAction()
    {
        $currentUserId = $this->getUserId();

        $users = $this->userManager->findUsers();

        if (!$users) {
            throw new NotFoundHttpException(
                $this->translator->trans('users.messages.users_not_found')
            );
        }
        return $this->templating->renderResponse(
            'BlogBundle:Users:index.html.twig',
            array(
                'users' => $users,
                'current_user_id' => $currentUserId
            )
        );
    }

    /**
     * Add action.
     *
     * @Route("/admin/users/add", name="users-add")
     * @Route("/admin/users/add")
     * @param Request $request
     * @param User|null $user
     * @return RedirectResponse
     */
    public function addAction(Request $request)
    {
        $userForm = $this->formFactory->create(
            new UserType(),
            null,
            array(
                'validation_groups' => 'user-default'
            )
        );

        $userForm->handleRequest($request);

        if ($userForm->isValid()) {
            $user = $userForm->getData();
            $this->userModel->save($user);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('users.messages.success.add')
            );
            return new RedirectResponse(
                $this->router->generate('users-index')
            );
        }
        return $this->templating->renderResponse(
            'BlogBundle:Users:add.html.twig',
            array('form' => $userForm->createView())
        );
    }

    /**
     * Edit action.
     *
     * @Route("/admin/users/edit/{id}", name="users-edit")
     * @Route("/admin/users/edit/{id}")
     * @ParamConverter("user", class="BlogBundle:User")
     * @param Request $request
     * @param User|null $user
     * @return RedirectResponse
     */
    public function editAction(Request $request, User $user = null)
    {
        $userForm = $this->formFactory->create(
            new UserType(),
            $user,
            array(
                'edit' => true
            )
        );

        $userForm->handleRequest($request);

        if ($userForm->isValid()) {
            $user = $userForm->getData();
            $this->userManager->updateUser($user);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('users.messages.success.edit')
            );
            return new RedirectResponse(
                $this->router->generate('users-index')
            );
        }
        return $this->templating->renderResponse(
            'BlogBundle:Users:edit.html.twig',
            array('form' => $userForm->createView())
        );
    }

    /**
     * Delete action.
     *
     * @Route("admin/users/delete/{id}", name="users-delete")
     * @Route("admin/users/delete/{id}/", name="users-delete")
     * @ParamConverter("user", class="BlogBundle:User")
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @internal param Post|null $post
     */
    public function deleteAction(Request $request, User $user = null)
    {
        $this->userModel->delete($user);
        $this->session->getFlashBag()->set(
            'success',
            $this->translator->trans('users.messages.success.delete')
        );
        return new RedirectResponse(
            $this->router->generate('users-index')
        );
    }

    /**
     * Change user role
     *
     * @Route("/admin/users/editRole/{id}", name="admin-user-edit-role")
     * @Route("/admin/users/editRole/{id}")
     * @ParamConverter("user", class="BlogBundle:User")
     * @param Request $request
     * @param User|null $user
     * @return RedirectResponse
     */
    public function editRoleAction(Request $request, User $user = null)
    {
        $currentUserId = $this->getUserId();

        //sprawdza, czy user istnieje oraz czy user do zmiany roli nie jest zalogowany
        if (!$user) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('admin.user_role.not_found')
            );
            return new RedirectResponse(
                $this->router->generate('users-index')
            );
        } elseif ($currentUserId === (int)$user->getId()) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('user.messages.cannot_change_role_currently_logged_id')
            );
            return new RedirectResponse(
                $this->router->generate('users-index')
            );
        }

        $changeRoleForm = $this->formFactory->create(
            new ChangeRoleType()
        );

        $changeRoleForm->handleRequest($request);

        if ($changeRoleForm->isValid()) {
            $choosenRole = $changeRoleForm->getData();
            $user->setRoles(array($choosenRole['role']));
            $this->userManager->updateUser($user);

            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('admin.user_role.change.success')
            );

            return new RedirectResponse(
                $this->router->generate('users-index')
            );
        }
        return $this->templating->renderResponse(
            'BlogBundle:Users:changeRole.html.twig',
            array('form' => $changeRoleForm->createView())
        );
    }

    /**
     * Returns user Id
     *
     * @return int
     */
    private function getUserId()
    {
        return (int)$this->securityContext->getToken()->getUser()->getId();
    }
}
