<?php


namespace App\Controller\Ajax;


use App\Controller\AppControllerAbstract;
use App\Helper\PartenaireFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PartenaireController  extends AppControllerAbstract
{
    /**
     * @Route("/ajax/partenaire/count/{filter?}", name="ajax_partenaire_count", methods={"POST"})
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function ajaxMyActionCountAction(
        Request $request,
        PartenaireFilter $partenaireFilter,
        ?string $filter): Response
    {
        ;
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $partenaireFilter->getData($filter)['nbr']);
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}