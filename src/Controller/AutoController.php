<?php

namespace App\Controller;

use App\Entity\Auto;
use App\Form\AutoType;
use App\Repository\AutoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;

class AutoController extends AbstractController
{
    /**
     * @Route("/auto", name="auto")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        
        $repo = $this->getDoctrine()->getRepository(Auto::class);
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
                            'label'=>'Url de l\'image',
                            'attr'=>['placeholder'=>'Entrez l\'url svp...']])
                          ->add('pays',TextType::class,[
                            'label'=>'Pays d\'origine',
                            'attr'=>['placeholder'=>'Entrez le pays svp..']])
                          ->add('Soumettre', SubmitType::class,['attr'=>['class'=>'col-12 btn btn-success']])
                          ->getForm();
        $form_auto->handleRequest($request);
        if($form_auto->isSubmitted() && $form_auto->isValid()){
            //dd($auto);
            $file = $form_auto->get('image')->getData();
            
            $fileName = time().'.'.$file->guessExtension();
            
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            $auto->setImage($fileName);
            $em->persist($auto);
            $em->flush();
            return $this->redirectToRoute("auto");
        }

       return $this->render('auto/add2.html.twig',[
           'form_car'=> $form_auto->createView()
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
                if(file_exists('images/'.$auto->getImage())){

                    $fileSystem->remove('images/'.$auto->getImage());
                }
                $auto->setImage($fileName);
            }
            
            $em->flush();
            return $this->redirectToRoute('auto_item', [
                'id' => $auto->getId()
            ]);

        }
       
        return $this->render('auto/edit.html.twig',['form_edit'=>$form_edit->createView(), 'auto'=>$auto]);
    }

    // /**
    //  * @Route("/delete/{id}", name="auto_delete")
    //  */
    // public function deleteAuto($id){

    //     $fileSystem = new Filesystem();

    //     $em = $this->getDoctrine()->getManager();
    //     $auto = $em->getRepository(Auto::class)->find($id);

    //     if(!$auto){
    //         throw $this->createNotFoundException(
    //             'Aucune voiture ne correspond à votre demande'
    //         );
    //     }
    //     if(file_exists('images/'.$auto->getImage())){

    //         $fileSystem->remove('images/'.$auto->getImage());
    //     }
    //     $em->remove($auto);
    //     $em->flush();

    //     return $this->redirectToRoute("auto");  
    //   }
}
