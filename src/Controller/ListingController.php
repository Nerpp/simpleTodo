<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Form\ListingType;
use App\Repository\ListingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/listing')]
class ListingController extends AbstractController
{
    #[Route('/{id}', name: 'app_listing_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Listing $listing, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ListingType::class, $listing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $listing->setName($listing->getName());
            $entityManager->persist($listing);
            $entityManager->flush();

            $this->addFlash('success', 'Liste éditer avec succés !');
            return $this->redirectToRoute('app_listing_show', ['id' => $listing->getid()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('listing/show.html.twig', [
            'listing' => $listing,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_listing_delete', methods: ['POST'])]
    public function delete(Request $request, Listing $listing, ListingRepository $listingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $listing->getId(), $request->request->get('_token'))) {
            $listingRepository->remove($listing, true);
        }
        $this->addFlash('success', 'Liste supprimer avec succés !');

        return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
    }
}
