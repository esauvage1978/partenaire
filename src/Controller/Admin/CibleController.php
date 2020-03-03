<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Dto\ContactDto;
use App\Entity\Role;
use App\Exportator\ContactExportator;
use App\Form\Admin\RoleNewType;
use App\Form\Admin\RoleType;
use App\Paginator\ContactPaginator;
use App\Repository\ContactDtoRepository;
use App\Repository\RoleRepository;
use App\Manager\RoleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/role")
 */
class RoleController extends AppControllerAbstract
{
    const ENTITYS = 'roles';
    const ENTITY = 'role';

    /**
     * @Route("/", name="role_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(RoleRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="role_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, RoleManager $manager): Response
    {
        $entity=new Role();
        $form = $this->createForm(RoleNewType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($entity)) {
                $this->addFlash(self::SUCCESS, self::MSG_CREATE);

                if (!empty($entity->getId())) {
                    return $this->redirectToRoute(self::ENTITY . '_edit', ['id' => $entity->getId()]);
                } else {
                    return $this->redirectToRoute(self::ENTITY . '_index');
                }
            }
            $this->addFlash(self::DANGER, self::MSG_ERROR . $manager->getErrors($entity));
        }

        return $this->render(self::ENTITY . '/new.html.twig', [
            self::ENTITY => $entity,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/export", name="role_export_contacts", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function export(
        Request $request,
        Role $role,
        ContactDtoRepository $repository
    ): Response
    {
        $dto = new ContactDto();
        $dto
            ->setRole($role);

        $contactExportator = new ContactExportator(
            $repository,
            $dto,
            $this->generateUrl('role_show',['id'=>$role->getId()]),
            'Consulter la role',
            ' pour la role ' . $role->getName()
        );

        return $this->render('contact/export.html.twig', [
            'contacts' => $contactExportator->getDatas(),
            'exportator' => $contactExportator->getParams()
        ]);
    }
    /**
     * @Route("/{id}/edit", name="role_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(
        Request $request,
        Role $entity,
        RoleManager $manager
    ): Response
    {
        $form = $this->createForm(RoleType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($entity)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

                if (!empty($entity->getId())) {
                    return $this->redirectToRoute(self::ENTITY . '_edit', ['id' => $entity->getId()]);
                } else {
                    return $this->redirectToRoute(self::ENTITY . '_index');
                }
            }
            $this->addFlash(self::DANGER, self::MSG_ERROR . $manager->getErrors($entity));
        }

        return $this->render(self::ENTITY . '/edit.html.twig', [
            self::ENTITY => $entity,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{page?<\d+>1}", name="role_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(
        Role $role,
        ContactDtoRepository $repository,
        $page=1
    ): Response
    {
        $dto = new ContactDto();
        $dto
            ->setPage($page)
            ->setRole($role);

        $contactPaginator = new ContactPaginator(
            $this->bag,
            $repository,
            $dto,
            ContactPaginator::VIGNETTE,
            $page
        );

        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $role,
            'contacts' => $contactPaginator->getDatas(),
            'paginator' => $contactPaginator->getParams()
        ]);
    }

    /**
     * @Route("/{id}", name="role_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Role $entity, RoleManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
