<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController

{
    /**
     * @Route("/program/", name="program_index")

     */
    public function index(): Response
    {
        return $this->render('program/home.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/program/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="program_show")
     */
    public function show(int $id): Response
    {
        return $this->render('program/show.html.twig', [
            'id' => $id,
        ]);
    }
}