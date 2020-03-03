<?php

namespace App\Controller\Admin;

use App\Controller\AppControllerAbstract;
use App\Dto\PartenaireDto;
use App\Entity\Category;
use App\Exportator\PartenaireExportator;
use App\Form\Admin\CategoryType;
use App\Paginator\PartenairePaginator;
use App\Repository\PartenaireDtoRepository;
use App\Repository\CategoryRepository;
use App\Manager\CategoryManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AppControllerAbstract
{
    const ENTITYS = 'categories';
    const ENTITY = 'category';

    /**
     * @Route("/", name="category_index", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(CategoryRepository $repository): Response
    {
        return $this->render(self::ENTITY.'/index.html.twig', [
            self::ENTITYS => $repository->findAllForAdmin(),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function newAction(Request $request, CategoryManager $manager): Response
    {
        $entity=new Category();
        $form = $this->createForm(CategoryType::class, $entity);

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
     * @Route("/{id}/export", name="category_export_partenaires", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function export(
        Request $request,
        Category $category,
        PartenaireDtoRepository $repository
    ): Response
    {
        $dto = new PartenaireDto();
        $dto
            ->setCategory($category);

        $contactExportator = new PartenaireExportator(
            $repository,
            $dto,
            $this->generateUrl('category_show',['id'=>$category->getId()]),
            'Consulter la ville',
            ' pour la ville ' . $category->getName()
        );

        return $this->render('partenaire/export.html.twig', [
            'partenaires' => $contactExportator->getDatas(),
            'exportator' => $contactExportator->getParams()
        ]);
    }
    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function editAction(
        Request $request,
        Category $entity,
        CategoryManager $manager,
        string $message = self::MSG_MODIFY
    ): Response
    {
        $form = $this->createForm(CategoryType::class, $entity);

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
     * @Route("/{id}/{page?<\d+>1}", name="category_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(
        Category $category,
        PartenaireDtoRepository $repository,
        $page=1
    ): Response
    {
        $dto = new PartenaireDto();
        $dto
            ->setPage($page)
            ->setCategory($category);

        $contactPaginator = new PartenairePaginator(
            $this->bag,
            $repository,
            $dto,
            PartenairePaginator::VIGNETTE,
            $page
        );

        return $this->render(self::ENTITY.'/show.html.twig', [
            self::ENTITY => $category,
            'partenaires' => $contactPaginator->getDatas(),
            'paginator' => $contactPaginator->getParams()
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Category $entity, CategoryManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }
}
