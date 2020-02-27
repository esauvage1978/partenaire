<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Entity\Fonction;
use App\Form\Admin\FonctionType;
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
     * @Route("/{id}", name="fonction_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Fonction $entity): Response
    {
        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $entity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fonction_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(Request $request, Fonction $entity, FonctionManager $manager, string $message = self::MSG_MODIFY): Response
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
     * @Route("/{id}", name="fonction_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Fonction $entity, FonctionManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
