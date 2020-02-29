<?php

namespace App\Controller\Home;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/user/contact", name="contact", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function contactAction(UserRepository $userRepository): Response
    {
        return $this->render('home/contact.html.twig',
            [
                'usersAdmin' => $userRepository->findAllForContactAdmin(),
                'usersGestionnaire' => $userRepository->findAllForContactGestionnaire()
            ]
        );
    }
}
