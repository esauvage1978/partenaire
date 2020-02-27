<?php


namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="user_login")
     * @return Response
     */
    public function loginAction(): Response
    {
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/logout", name="user_logout", methods={"GET"})
     */
    public function logout()
    {
    }
}
