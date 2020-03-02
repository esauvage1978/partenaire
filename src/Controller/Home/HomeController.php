<?php

namespace App\Controller\Home;

use App\Dto\ContactDto;
use App\Dto\PartenaireDto;
use App\Repository\ContactDtoRepository;
use App\Repository\PartenaireDtoRepository;
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
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /**
     * @Route("/search/", name="home_search", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function homeSearchAction(
        ContactDtoRepository $contactRepo,
        PartenaireDtoRepository $partenaireRepo,
        ContactDto $contactDto,
        PartenaireDto $partenaireDto,
        Request $request
    ): Response
    {
        $contactDto->setWordSearch($request->request->get('search'));
        $partenaireDto->setWordSearch($request->request->get('search'));

        return $this->render(
            'home/search.html.twig',
            [
                'contacts'=>$contactRepo->findAllForDto($contactDto),
                'partenaires'=>$partenaireRepo->findAllForDto($partenaireDto),
            ]);
    }
}
