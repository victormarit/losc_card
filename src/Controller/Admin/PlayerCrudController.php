<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlayerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Player::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $product = new Player();

        return $product;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname')->setLabel("Prénom"),
            TextField::new('lastname')->setLabel("Nom"),
            NumberField::new('getYellowCardsNumber')->setLabel("Cartons Jaunes")->onlyOnIndex(),
            NumberField::new('getRedCardsNumber')->setLabel("Cartons Rouges")->onlyOnIndex(),
        ];
    }
}
