<?php

namespace App\Controller\Home;

use App\Dto\ContactDto;
use App\Repository\ContactDtoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /**
     * @Route("/search/", name="home_search", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function homeSearchAction(
        ContactDtoRepository $Contactrepo,
        ContactDto $contactDto,
        Request $request
    ): Response
    {
        $contactDto->setWord($request->request->get('search'));

        return $this->render(
            'home/search.html.twig',
            [
                'contacts'
                =>
                    $Contactrepo->findAllForDto($contactDto),
            ]);
    }
}
