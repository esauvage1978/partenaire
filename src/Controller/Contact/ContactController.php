<?php

namespace App\Controller\Contact;

use App\Controller\AppControllerAbstract;
use App\Dto\ContactDto;
use App\Entity\Contact;
use App\Exportator\ContactExportator;
use App\Form\Contact\ContactDtoExportType;
use App\Form\Contact\ContactDtoType;
use App\Form\Contact\ContactType;
use App\Manager\ContactManager;
use App\Paginator\ContactPaginator;
use App\Repository\ContactDtoRepository;
use App\Service\CommentPaginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AppControllerAbstract
{

    const ENTITYS = 'contacts';
    const ENTITY = 'contact';


    /**
     * @Route("/contact/new", name="contact_new", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_EDITEUR")
     */
    public function newAction(Request $request, ContactManager $manager): Response
    {
        return $this->edit(
            $request,
            new Contact(),
            $manager,
            self::ENTITY,
            ContactType::class,
            self::MSG_CREATE
        );
    }

    /**
     * @Route("/contact/{id}", name="contact_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showAction(
        Contact $contact
    ): Response
    {
        return $this->render(self::ENTITY . '/show.html.twig',
            [
                self::ENTITY => $contact
            ]
        );
    }

    /**
     * @Route("/contact/{id}/edit", name="contact_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function editAction(
        Request $request,
        Contact $entity,
        ContactManager $manager,
        string $message = self::MSG_MODIFY
    ): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            ContactType::class,
            $message
        );
    }


    /**
     * @Route("/contacts", name="contact_index", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function index(
        ContactDtoRepository $repository,
        Request $request
    ): Response
    {
        $contactDto = new ContactDto();
        $form = $this->createForm(ContactDtoType::class, $contactDto, ['attr' => ['name' => 'contactManu']]);
        $form->handleRequest($request);

        $contactPaginator = new ContactPaginator(
            $this->bag,
            $repository,
            $contactDto,
            ContactPaginator::GRID,
            $contactDto->getPage()
        );

        $contactDto2 = new ContactDto();
        $formExport = $this->createForm(ContactDtoExportType::class, $contactDto2, [
            'action' => $this->generateUrl('contact_export'),
            'method' => 'POST',
        ]);


        return $this->render(self::ENTITY . '/index.html.twig', [
            self::ENTITYS => $contactPaginator->getDatas(),
            self::FORM => $form->createView(),
            'formexport' => $formExport->createView(),
            'paginator' => $contactPaginator->getParams()
        ]);
    }

    /**
     * @Route("/contacts/export", name="contact_export", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function export(
        ContactDtoRepository $repository,
        Request $request
    ): Response
    {
        $dto = new ContactDto();
        $form = $this->createForm(ContactDtoExportType::class, $dto);

        $form->handleRequest($request);

        $contactExportator = new ContactExportator(
            $repository,
            $dto,
            $this->generateUrl('contact_index'),
            'Consulter la recherche',
            ' pour la recherche '
        );

        return $this->render('contact/export.html.twig', [
            'contacts' => $contactExportator->getDatas(),
            'exportator' => $contactExportator->getParams()
        ]);
    }


    /**
     * @Route("/{id}", name="contact_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function deleteAction(Request $request, Contact $entity, ContactManager $manager): Response
    {
        return $this->delete($request, $entity, $manager, self::ENTITY);
    }

}
