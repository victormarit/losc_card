<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use App\Entity\Suspension;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Exception;

class CardCrudController extends AbstractCrudController
{

    const DATE_FORMAT = "Y-m-d H:i:s";

    public static function getEntityFqcn(): string
    {
        return Card::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('cardColor')->setLabel("Type de carton")->onlyOnIndex(),
            TextField::new('firstnamePlayer')->setLabel("Prénom")->onlyOnIndex(),
            TextField::new('namePlayer')->setLabel("Nom")->onlyOnIndex(),
            TextField::new('catName')->setLabel("Catégorie")->onlyOnIndex(),
            DateField::new('dateName')->setLabel("Date")->onlyOnIndex(),
            AssociationField::new("player")->setRequired(True)->onlyOnForms(),
            AssociationField::new("game")->setRequired(True)->onlyOnForms(),
            AssociationField::new("cardType")->setRequired(True)->onlyOnForms(),

        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->manageSuspension($entityManager, $entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);
        $this->manageSuspension($entityManager, $entityInstance);
    }

    /**
     * @throws Exception
     */
    public function manageSuspension(EntityManagerInterface $entityManager, $entityInstance){
        if($entityInstance->getCardType()->getName() == "Red"){
            $suspension = new Suspension();
            $suspension->setNbGame(2);
            $date = new DateTime($entityInstance->getGame()->getGameDate()->format(self::DATE_FORMAT));
            $date->modify('+1 day');
            $suspension->setBeginDate($date);
            $suspension->setPlayer($entityInstance->getPlayer());
            $entityManager->persist($suspension);
            $entityManager->flush();
        }
        else{
            $playersCards = $entityInstance->getPlayer()->getActiveYellowCards($entityInstance->getGame()->getGameDate()->format(self::DATE_FORMAT));
            if(count($playersCards) > 1) {
                $suspension = new Suspension();
                $suspension->setNbGame(1);
                $date = new DateTime($entityInstance->getGame()->getGameDate()->format(self::DATE_FORMAT));
                $date->modify('+1 day');
                $suspension->setBeginDate($date);
                $suspension->setPlayer($entityInstance->getPlayer());
                $entityManager->persist($suspension);
                $entityManager->flush();
            }
        }
    }



    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud{
        return $crud
            ->setDefaultSort(['id' => 'DESC']);
    }

}
