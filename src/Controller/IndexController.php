<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Form\ListingType;
use App\Repository\ListingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ListingRepository $listingRepository, ManagerRegistry $doctrine): Response
    {
        $listing = new Listing();
        $form = $this->createForm(ListingType::class, $listing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $listing->setName($listing->getName());
            $entityManager->persist($listing);
            $entityManager->flush();

            $this->addFlash('success', 'Liste créer avec succés !');
            return $this->redirectToRoute('app_listing_show', ['id' => $listing->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'listing' => $listingRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }
}
