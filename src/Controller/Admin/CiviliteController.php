<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Dto\ContactDto;
use App\Entity\Civilite;
use App\Exportator\ContactExportator;
use App\Form\Admin\CiviliteType;
use App\Paginator\ContactPaginator;
use App\Repository\ContactDtoRepository;
use App\Repository\CiviliteRepository;
use App\Manager\CiviliteManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/civilite")
 */
class CiviliteController extends AppControllerAbstract
{
    const ENTITYS = 'civilites';
    const ENTITY = 'civilite';

    /**
     * @Route("/", name="civilite_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(CiviliteRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="civilite_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, CiviliteManager $manager): Response
    {
        return $this->editAction($request, new Civilite(), $manager, self::MSG_CREATE);
    }

    /**
     * @Route("/{id}/export", name="civilite_export_contacts", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function export(
        Request $request,
        Civilite $civilite,
        ContactDtoRepository $repository
    ): Response
    {
        $dto = new ContactDto();
        $dto
            ->setCivilite($civilite);

        $contactExportator = new ContactExportator(
            $repository,
            $dto,
            $this->generateUrl('civilite_show',['id'=>$civilite->getId()]),
            'Consulter la civilité',
            ' pour la civilité ' . $civilite->getName()
        );

        return $this->render('contact/export.html.twig', [
            'contacts' => $contactExportator->getDatas(),
            'exportator' => $contactExportator->getParams()
        ]);
    }
    /**
     * @Route("/{id}/edit", name="civilite_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(
        Request $request,
        Civilite $entity,
        CiviliteManager $manager,
        string $message = self::MSG_MODIFY
    ): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            CiviliteType::class,
            $message
        );
    }

    /**
     * @Route("/{id}/{page?<\d+>1}", name="civilite_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(
        Civilite $civilite,
        ContactDtoRepository $repository,
        $page=1
    ): Response
    {
        $dto = new ContactDto();
        $dto
            ->setPage($page)
            ->setCivilite($civilite);

        $contactPaginator = new ContactPaginator(
            $this->bag,
            $repository,
            $dto,
            ContactPaginator::VIGNETTE,
            $page
        );

        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $civilite,
            'contacts' => $contactPaginator->getDatas(),
            'paginator' => $contactPaginator->getParams()
        ]);
    }




    /**
     * @Route("/{id}", name="civilite_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Civilite $entity, CiviliteManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
