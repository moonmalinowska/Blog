<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 06.05.16
 * Time: 14:40
 *
 * Pages controller class.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Entity\Enquiry;
use BlogBundle\Form\EnquiryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Doctrine\Common\Persistence\ObjectRepository;
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

/**
 * Class PagesController.
 *
 * @Route(service="app.pages_controller")
 *
 * @package BlogBundle\Controller
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 * @author Monika Malinowska
 * @copyright (c) 2016 Monika Malinowska
 */
class PagesController extends Controller
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
     * My email.
     *
     * @var FormFactory $my_email
     */
    protected $my_email;

    /**
     * Mailer
     *
     * @var FormFactory $mailer
     */
    private $mailer;
    /**
     * Forward
     *
     * @var Forward $forward
     */
    private $forward;

    /**
     * PagesController constructor.
     *
     * @param Translator $translator Translator
     * @param EngineInterface $templating Templating engine
     * @param Session $session Session
     * @param RouterInterface $router
     * @param ObjectRepository $model Model object
     * @param FormFactory $formFactory Form factory
     * @param $my_email
     * @param $mailer
     * @internal param $
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        ObjectRepository $model,
        FormFactory $formFactory,
        $my_email,
        $mailer
    ) {
    
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->model = $model;
        $this->formFactory = $formFactory;
        $this->my_email = $my_email;
        $this->mailer = $mailer;
    }

    /**
     * Index action.
     *
     * @Route("/")
     * @Route("/index", name="index")
     * @Route("/index/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function indexAction()
    {
        //$this->forward('BlogBundle:Post:indexAction');

       /** $posts = $em->createQueryBuilder()
            ->select('b')
            ->from('BlogBundle:Post',  'b')
            ->addOrderBy('b.created', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->templating->renderResponse(
            'BlogBundle:Page:index.html.twig',
            array(
        'posts' => $posts
        ));**/
        //return $response;
        return new RedirectResponse($this->router->generate('BlogBundle_posts'));
    }

    /**
     * Index action.
     *
     * @Route("/about", name="about")
     * @Route("/about/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function aboutAction()
    {
        return $this->templating->renderResponse('BlogBundle:Pages:about.html.twig');
    }

    /**
     * Contact action.
     *
     * @Route("/contact", name="contact")
     * @Route("/contact/")
     * @param Request $request
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();
        $enquiryForm = $this->formFactory->create(
            new EnquiryType(),
            //null,
            $enquiry
        );

        /*$request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $enquiryForm->bind($request);*/
        
        $enquiryForm->handleRequest($request);

        if ($enquiryForm->isValid()) {
            $message = \Swift_Message::newInstance()
            ->setSubject('Kontakt z bloga')
            ->setFrom('enquiries@gmail.com')
            ->setTo($this->my_email)
            ->setBody($this->templating->renderResponse(
                'BlogBundle:Pages:contactEmail.txt.twig',
                array('enquiry' => $enquiry)
            ));

            try {
                $this->mailer->send($message);
                $this->session->getFlashBag()->add(
                    'success',
                    $this->translator->trans('email.messages.success')
                );
            } catch (\Swift_TransportException $Ste) {
                $this->session->getFlashBag()->add(
                    'warning',
                    $this->translator->trans('email.messages.fail')
                );
            }
            

            return new RedirectResponse(
                $this->router->generate('BlogBundle_contact')
            );
        }
        
            return $this->templating->renderResponse(
                'BlogBundle:Pages:contact.html.twig',
                array('form' => $enquiryForm->createView()
                )
            );

    }
}
