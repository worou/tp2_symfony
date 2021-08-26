<?php

namespace App\Controller;

use App\Entity\Auto;
use App\Form\AutoType;
use App\Form\ContactType;
use App\Service\AutoService;
use Symfony\Component\Mime\Email;
use App\Repository\AutoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AutoController extends AbstractController
{
    private $autoService;
    private $session;

    public function __construct(AutoService $autoS, SessionInterface $session){
        $this->autoService = $autoS;
        $this->session = $session;
    }
    /**
     * @Route("/auto", name="auto")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        
        $repo = $this->getDoctrine()->getRepository(Auto::class);
        $cars = $repo->findBy(['puissance'=>428],['id'=>'DESC']);
        $this->session->set('cars',$cars);
        $autosData = $repo->findAll();
        $autosPagination = $paginator->paginate(
            $autosData,
            $request->query->getInt('page',1)//le numero de la page par défaut
        );
        //dd($autos);
        return $this->render('auto/index.html.twig', [
            'autos' => $autosPagination,
        ]);
    }

    /**
     * @Route("/auto/{id}", name="auto_item")
     */
    // public function getAuto(int $id){
        
    //     $repo = $this->getDoctrine()->getRepository(Auto::class);
    //     $auto = $repo->find($id);
       
    //     return $this->render('auto/detail.html.twig', ['auto'=>$auto]);
    // }

    
    /**
     * @Route("/auto/{id}", name="auto_item")
     */
    // public function getAuto(AutoRepository $repo, $id){

    //     $auto = $repo->find($id);

    //     return $this->render('auto/detail.html.twig', ['auto'=>$auto]);
    // }

    /**
     * @Route("/auto/{id}", name="auto_item")
     */
    public function getAuto(Auto $auto){
        
        return $this->render('auto/detail.html.twig', ['auto'=>$auto]);
    }

    /**
     * @Route("/new", name="auto_new")
     */
    public function create(Request $request){
        
        if($request->request->get('marque')){
            //dd($request->get('marque'));
            $em = $this->getDoctrine()->getManager();

            $auto = new Auto();
            $auto->setMarque($request->get('marque'));
            $auto->setModele($request->get('modele'));
            $auto->setPuissance($request->get('puissance'));
            $auto->setPrix(100000);
            $auto->setPays($request->get('pays'));
            $auto->setImage($request->get('image'));

            $em->persist($auto);
            $em->flush();

            return $this->redirectToRoute("auto");
        }
        return $this->render('auto/add.html.twig');
    }

    /**
     * @Route("/add", name="add_auto")
     */
    public function addForm(Request $request, EntityManagerInterface $em){
        $cars = $this->session->get('cars');
        $auto = new Auto();
        $form_auto = $this->createFormBuilder($auto)
                          ->add('marque',TextType::class,[
                            'label'=>'Marque de la voiture',
                            'attr'=>['placeholder'=>'Entrez la marque svp...']])
                          ->add('modele',TextType::class, [
                             'label'=>'Modèle de la voiture',
                             'attr'=>[
                                 'placeholder'=>'Entrer le modèle ...'
                             ]
                              ])
                          ->add('prix', MoneyType::class,[
                              'label'=>'Prix de la voiture',
                              'attr'=>['placeholder'=>'Entrez le prix svp...']])
                          ->add('puissance',IntegerType::class,[
                            'label'=>'Puissance de la voiture',
                            'attr'=>['placeholder'=>'Entrez la puissance svp...']])
                          ->add('image', FileType::class,[
                            'label'=>'Upload de l\'image','required' => false,
                            'attr'=>['placeholder'=>'Entrez l\'url svp...']])
                          ->add('pays',TextType::class,[
                            'label'=>'Pays d\'origine',
                            'attr'=>['placeholder'=>'Entrez le pays svp..']])
                          ->add('Soumettre', SubmitType::class,['attr'=>['class'=>'col-12 btn btn-success']])
                          ->getForm();
        $form_auto->handleRequest($request);
        if($form_auto->isSubmitted() && $form_auto->isValid()){
            //dd($auto);
            // $file = $form_auto->get('image')->getData();
            
            // $fileName = time().'.'.$file->guessExtension();
            
            // $file->move(
            //     $this->getParameter('images_directory'),
            //     $fileName
            // );
            $images_destination = $this->getParameter('images_directory');
            $fileName = $this->autoService->upload($form_auto, $images_destination);
            $auto->setImage($fileName);
            $em->persist($auto);
            $em->flush();

            $this->addFlash('success', 'Voiture ajoutée avec succès');
            return $this->redirectToRoute("auto");
        }

       return $this->render('auto/add2.html.twig',[
           'form_car'=> $form_auto->createView(),
           'cars'=>$cars
       ]);
    }

    /**
     * @Route("/auto/edit/{id}", name="auto_edit")
     */
    public function update(int $id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $auto = $em->getRepository(Auto::class)->find($id);

        $form_edit = $this->createForm(AutoType::class,$auto);
        if (!$auto) {
            throw $this->createNotFoundException(
                'Aucune voiture correspond à cet id '.$id
            );
        }
        $oldfilename = $auto->getImage();
        $form_edit->handleRequest($request);
        if($form_edit->isSubmitted() && $form_edit->isValid()){
            $fileSystem = new Filesystem();
            $file = $form_edit->get('image')->getData();
            $fileName = "";
            if($file){
                $fileName = time().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
                if(file_exists('images/'.$oldfilename)){

                    $fileSystem->remove('images/'.$oldfilename);
                }
                $auto->setImage($fileName);
            }else{
                $auto->setImage($oldfilename);
            }
            
            $em->flush();
            $this->addFlash('success', 'Voiture N° '.$auto->getId().' a été modifiée avec succès...');
            return $this->redirectToRoute('auto_item', [
                'id' => $auto->getId()
            ]);

        }
       
        return $this->render('auto/edit.html.twig',[
            'form_edit'=>$form_edit->createView(), 
            'auto'=>$auto
            
        ]);
    }

    /**
     * @Route("/delete/{id}", name="auto_delete")
     */
    public function deleteAuto($id){

        $fileSystem = new Filesystem();

        $em = $this->getDoctrine()->getManager();
        $auto = $em->getRepository(Auto::class)->find($id);

        if(!$auto){
            throw $this->createNotFoundException(
                'Aucune voiture ne correspond à votre demande'
            );
        }
        if(file_exists('images/'.$auto->getImage())){

            $fileSystem->remove('images/'.$auto->getImage());
        }
        $em->remove($auto);
        $em->flush();
        $this->addFlash('success', 'Voiture N° '.$auto->getId().' a été supprimée avec succès...');
        return $this->redirectToRoute("auto");  
      }

    /**
     * @Route("/email-contact", name="email_contact")
     */
    public function sendEmail(MailerInterface $mailer):Response
    {
        
        $email = (new Email())
            ->from('dwwm94@gmail.com')
            ->to('dwwm94@gmail.com')
            ->cc('adimicool@gmail.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return new Response('Email envoyé');
    }

    /**
     * @Route("/send-mail", name="send_mail")
     */
    public function sendMail(Request $request, MailerInterface $mailer){
        $form_contact = $this->createForm(ContactType::class);
        $form_contact->handleRequest($request);

        if ($form_contact->isSubmitted() && $form_contact->isValid()) {
            $contact = $form_contact->getData();
            //dd($contact['email']);
            $email = (new TemplatedEmail())
            ->from('dwwm94@gmail.com')
            ->to($contact['email'])
            ->subject($contact['subject'])

            // path of the Twig template to render
            ->htmlTemplate('emails/message.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
                'subject'=>$contact['subject'],
                'message'=>$contact['message']
            ]);
            $mailer->send($email);
            return $this->redirectToRoute('success_message');
        }
        return $this->render('auto/contact.html.twig',[
            'formContact'=>$form_contact->createView()
        ]);
    }
    /**
     * @Route("/success-message", name="success_message")
     *
     */
    public function confirmation(){
        return new Response('Confirmation');
    }
}
