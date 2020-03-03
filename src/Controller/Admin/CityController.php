<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Dto\PartenaireDto;
use App\Entity\City;
use App\Exportator\PartenaireExportator;
use App\Form\Admin\CityType;
use App\Paginator\PartenairePaginator;
use App\Repository\PartenaireDtoRepository;
use App\Repository\CityRepository;
use App\Manager\CityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/city")
 */
class CityController extends AppControllerAbstract
{
    const ENTITYS = 'cities';
    const ENTITY = 'city';

    /**
     * @Route("/", name="city_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(CityRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="city_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, CityManager $manager): Response
    {
        $entity=new City();
        $form = $this->createForm(CityType::class, $entity);

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
     * @Route("/{id}/export", name="city_export_partenaires", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function export(
        Request $request,
        City $city,
        PartenaireDtoRepository $repository
    ): Response
    {
        $dto = new PartenaireDto();
        $dto
            ->setCity($city);

        $contactExportator = new PartenaireExportator(
            $repository,
            $dto,
            $this->generateUrl('city_show',['id'=>$city->getId()]),
            'Consulter la ville',
            ' pour la ville ' . $city->getName()
        );

        return $this->render('partenaire/export.html.twig', [
            'partenaires' => $contactExportator->getDatas(),
            'exportator' => $contactExportator->getParams()
        ]);
    }
    /**
     * @Route("/{id}/edit", name="city_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(
        Request $request,
        City $entity,
        CityManager $manager,
        string $message = self::MSG_MODIFY
    ): Response
    {
        $form = $this->createForm(CityType::class, $entity);

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
     * @Route("/{id}/{page?<\d+>1}", name="city_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(
        City $city,
        PartenaireDtoRepository $repository,
        $page=1
    ): Response
    {
        $dto = new PartenaireDto();
        $dto
            ->setPage($page)
            ->setCity($city);

        $contactPaginator = new PartenairePaginator(
            $this->bag,
            $repository,
            $dto,
            PartenairePaginator::VIGNETTE,
            $page
        );

        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $city,
            'partenaires' => $contactPaginator->getDatas(),
            'paginator' => $contactPaginator->getParams()
        ]);
    }

    /**
     * @Route("/{id}", name="city_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, City $entity, CityManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
