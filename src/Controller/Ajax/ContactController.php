<?php


namespace App\Controller\Ajax;


use App\Controller\AppControllerAbstract;
use App\Helper\ContactFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ContactController  extends AppControllerAbstract
{
    /**
     * @Route("/ajax/contact/count/{filter?}", name="ajax_contact_count", methods={"POST"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function ajaxMyActionCountAction(
        Request $request,
        ContactFilter $contactFilter,
        ?string $filter): Response
    {
        ;
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $contactFilter->getData($filter)['nbr']);
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}