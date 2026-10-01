<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\StockMovement;
use App\Entity\User;
use App\Enum\MovementType;
use Doctrine\ORM\EntityManagerInterface;

class StockService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function record(
        Product $product,
        MovementType $type,
        int $quantity,
        User $user,
        ?string $reason = null,
    ): StockMovement {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('La quantité doit être supérieure à 0.');
        }

        if (!$product->isActive()) {
            throw new \DomainException('Ce produit est désactivé.');
        }

        $reason = $reason !== null ? trim($reason) : null;
        $needsReason = in_array($type, [MovementType::ADJUSTMENT, MovementType::WASTE], true);
        if ($needsReason && ($reason === null || $reason === '')) {
            throw new \InvalidArgumentException('Un motif est obligatoire pour ce type de mouvement.');
        }

        $delta = match ($type) {
            MovementType::IN, MovementType::ADJUSTMENT => $quantity,
            MovementType::OUT, MovementType::WASTE => -$quantity,
        };

        return $this->em->wrapInTransaction(function () use ($product, $type, $quantity, $user, $reason, $delta) {
            $newStock = $product->getCurrentStock() + $delta;
            if ($newStock < 0) {
                throw new \DomainException(sprintf(
                    'Stock insuffisant : %d disponible(s).',
                    $product->getCurrentStock()
                ));
            }

            $product->setCurrentStock($newStock);

            $movement = (new StockMovement())
                ->setProduct($product)
                ->setType($type)
                ->setQuantity($quantity)
                ->setReason($reason)
                ->setCreatedBy($user);

            $this->em->persist($movement);

            return $movement;
        });
    }
}