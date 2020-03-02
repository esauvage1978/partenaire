<?php

namespace App\Controller\Partenaire;

use App\Controller\AppControllerAbstract;
use App\Dto\PartenaireDto;
use App\Entity\Partenaire;
use App\Exportator\PartenaireExportator;
use App\Form\Partenaire\PartenaireDtoExportType;
use App\Form\Partenaire\PartenaireDtoType;
use App\Form\Partenaire\PartenaireType;
use App\History\PartenaireHistory;
use App\History\HistoryShow;
use App\Manager\PartenaireManager;
use App\Paginator\PartenairePaginator;
use App\Repository\PartenaireDtoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartenaireController extends AppControllerAbstract
{

    const ENTITYS = 'partenaires';
    const ENTITY = 'partenaire';


    /**
     * @Route("/partner/new", name="partenaire_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_EDITEUR")
     */
    public function newAction(Request $request, PartenaireManager $manager): Response
    {
        $entity=new Partenaire();
        $form = $this->createForm(PartenaireType::class, $entity);

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
     * @Route("/partner/{id}", name="partenaire_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public
    function deleteAction(Request $request, Partenaire $entity, PartenaireManager $manager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $manager->remove($entity);
        }
        return $this->redirectToRoute('partenaire_index');
    }

    /**
     * @Route("/partner/{id}", name="partenaire_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(
        Partenaire $partenaire
    ): Response
    {
        return $this->render(self::ENTITY . '/show.html.twig',
            [
                self::ENTITY => $partenaire
            ]
        );
    }

    /**
     * @Route("/partner/{id}/edit", name="partenaire_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function editAction(
        Request $request,
        Partenaire $entity,
        PartenaireManager $manager,
        PartenaireHistory $partenaireHistory
    ): Response
    {
        $partenaireOld = clone($entity);
        $form = $this->createForm(PartenaireType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($entity)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

                $partenaireHistory->compare($partenaireOld, $entity);

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
     * @Route("/partner/{id}/history", name="partenaire_history", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function history(
        Request $request,
        Partenaire $entity
    ): Response
    {
        $historyShow=new HistoryShow(
            $this->generateUrl('partenaire_edit',['id'=>$entity->getId()]),
            "Partenaire : " . $entity->getName(),
            "Historiques des modifications du partenaire"
        );
        return $this->render('history/show.html.twig', [
            'histories' => $entity->getHistories(),
            'data'=>$historyShow->getParams()
        ]);
    }

    /**
     * @Route("/partners", name="partenaire_index", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public
    function index(
        PartenaireDtoRepository $repository,
        Request $request
    ): Response
    {
        $partenaireDto = new PartenaireDto();
        $partenaireDto->setEnable(PartenaireDto::TRUE);
        $form = $this->createForm(PartenaireDtoType::class, $partenaireDto, ['attr' => ['name' => 'partenaireManu']]);
        $form->handleRequest($request);

        $partenairePaginator = new PartenairePaginator(
            $this->bag,
            $repository,
            $partenaireDto,
            PartenairePaginator::GRID,
            $partenaireDto->getPage()
        );

        $partenaireDto2 = new PartenaireDto();

        $formExport = $this->createForm(PartenaireDtoExportType::class, $partenaireDto2, [
            'action' => $this->generateUrl('partenaire_export'),
            'method' => 'POST',
        ]);


        return $this->render(self::ENTITY . '/index.html.twig', [
            self::ENTITYS => $partenairePaginator->getDatas(),
            self::FORM => $form->createView(),
            'formexport' => $formExport->createView(),
            'paginator' => $partenairePaginator->getParams()
        ]);
    }

    /**
     * @Route("/partners/export", name="partenaire_export", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public
    function export(
        PartenaireDtoRepository $repository,
        Request $request
    ): Response
    {
        $dto = new PartenaireDto();
        $form = $this->createForm(PartenaireDtoExportType::class, $dto);

        $form->handleRequest($request);

        $partenaireExportator = new PartenaireExportator(
            $repository,
            $dto,
            $this->generateUrl('partenaire_index'),
            'Consulter la recherche',
            ' pour la recherche '
        );

        return $this->render('partenaire/export.html.twig', [
            'partenaires' => $partenaireExportator->getDatas(),
            'exportator' => $partenaireExportator->getParams()
        ]);
    }




}
