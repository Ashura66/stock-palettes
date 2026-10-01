<?php

namespace App\Controller;

use App\Entity\StockMovement;
use App\Entity\User;
use App\Form\StockMovementFormType;
use App\Repository\StockMovementRepository;
use App\Service\StockService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/movement')]
final class MovementController extends AbstractController
{
    #[Route(name: 'app_movement_index', methods: ['GET'])]
    public function index(StockMovementRepository $repository): Response
    {
        return $this->render('movement/index.html.twig', [
            'movements' => $repository->findBy([], ['createdAt' => 'DESC'], 50),
        ]);
    }

    #[Route('/new', name: 'app_movement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StockService $stockService): Response
    {
        $movement = new StockMovement();
        $form = $this->createForm(StockMovementFormType::class, $movement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            try {
                $stockService->record(
                    $movement->getProduct(),
                    $movement->getType(),
                    $movement->getQuantity(),
                    $user,
                    $movement->getReason(),
                );
                $this->addFlash('success', 'Mouvement enregistré.');

                return $this->redirectToRoute('app_movement_index');
            } catch (\DomainException|\InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('movement/new.html.twig', ['form' => $form]);
    }
}