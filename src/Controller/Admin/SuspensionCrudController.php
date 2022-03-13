<?php

namespace App\Controller\Admin;

use App\Entity\Suspension;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class SuspensionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Suspension::class;
    }


    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud{
        return $crud
            ->setDefaultSort(["beginDate" => "DESC", 'id' => 'DESC']);
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstnamePlayer')->setLabel("Prénom")->onlyOnIndex(),
            TextField::new('namePlayer')->setLabel("Nom")->onlyOnIndex(),
            NumberField::new('nbGame')->setLabel("Nombre de match de suspension"),
            NumberField::new('nbGame')->setLabel("Nombre de match de suspension"),
            DateField::new('beginDate')->setLabel("Début suspension"),
            AssociationField::new("player")->setRequired(True)->onlyOnForms(),
        ];
    }
}
