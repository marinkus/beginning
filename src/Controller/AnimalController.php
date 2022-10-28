<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Animal;


class AnimalController extends AbstractController
{

    public function __construct()
    {
        $this->r = Request::createFromGlobals();
    }

    #[Route('/animal/create', name: 'create', methods: 'GET')]
    public function create(): Response
    {
        return $this->render('animal/create.html.twig');
    }

    #[Route('/animal/save', name: 'save', methods: 'POST')]
    public function save(ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();

        $animal = new Animal();
        $animal->setName($this->r->request->get('name'));

        $entityManager->persist($animal);
        $entityManager->flush();

        return $this->redirectToRoute('list');
    }

    #[Route('/animal', name: 'list', methods: 'GET')]
    public function list(ManagerRegistry $doctrine): Response
    {

        $animals = $doctrine->getRepository(Animal::class)->findAll();

        return $this->render('animal/list.html.twig', [
            'animals' => $animals
        ]);
    }
    #[Route('/animal/edit/{id}', name: 'edit', methods: 'GET')]
    public function edit(ManagerRegistry $doctrine, int $id): Response
    {

        $animal = $doctrine->getRepository(Animal::class)->find($id);
        return $this->render('animal/edit.html.twig', [
            'animal' => $animal
        ]);
    }
    #[Route('/animal/update/{id}', name: 'update', methods: 'POST')]
    public function update(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        $animal = $doctrine->getRepository(Animal::class)->find($id);
        $animal->setName($this->r->request->get('name'));

        $entityManager->flush();

        return $this->redirectToRoute('list');
    }
}
