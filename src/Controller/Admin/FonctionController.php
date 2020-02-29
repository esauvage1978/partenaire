<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Dto\ContactDto;
use App\Entity\Fonction;
use App\Exportator\ContactExportator;
use App\Form\Admin\FonctionType;
use App\Paginator\ContactPaginator;
use App\Repository\ContactDtoRepository;
use App\Repository\FonctionRepository;
use App\Manager\FonctionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/fonction")
 */
class FonctionController extends AppControllerAbstract
{
    const ENTITYS = 'fonctions';
    const ENTITY = 'fonction';

    /**
     * @Route("/", name="fonction_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(FonctionRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="fonction_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, FonctionManager $manager): Response
    {
        return $this->editAction($request, new Fonction(), $manager, self::MSG_CREATE);
    }

    /**
     * @Route("/{id}/export", name="fonction_export_contacts", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function export(
        Request $request,
        Fonction $fonction,
        ContactDtoRepository $repository
    ): Response
    {
        $dto = new ContactDto();
        $dto
            ->setFonction($fonction);

        $contactExportator = new ContactExportator(
            $repository,
            $dto,
            $this->generateUrl('fonction_show',['id'=>$fonction->getId()]),
            'Consulter la fonction',
            ' pour la fonction ' . $fonction->getName()
        );

        return $this->render('contact/export.html.twig', [
            'contacts' => $contactExportator->getDatas(),
            'exportator' => $contactExportator->getParams()
        ]);
    }
    /**
     * @Route("/{id}/edit", name="fonction_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(
        Request $request,
        Fonction $entity,
        FonctionManager $manager,
        string $message = self::MSG_MODIFY
    ): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            FonctionType::class,
            $message
        );
    }

    /**
     * @Route("/{id}/{page?<\d+>1}", name="fonction_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(
        Fonction $fonction,
        ContactDtoRepository $repository,
        $page=1
    ): Response
    {
        $dto = new ContactDto();
        $dto
            ->setPage($page)
            ->setFonction($fonction);

        $contactPaginator = new ContactPaginator(
            $this->bag,
            $repository,
            $dto,
            ContactPaginator::VIGNETTE,
            $page
        );

        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $fonction,
            'contacts' => $contactPaginator->getDatas(),
            'paginator' => $contactPaginator->getParams()
        ]);
    }




    /**
     * @Route("/{id}", name="fonction_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Fonction $entity, FonctionManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
