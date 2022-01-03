<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Season;

use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;

use App\Service\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Form\ProgramType;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController

{
      /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }

     /**
     * The controller for the category add form
     *
     * @Route("/new", name="new")
     */

    public function new(Request $request) : Response
    {
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
         // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Program Object
            $entityManager->persist($program);
            $entityManager->flush();
            // Finally redirect to program list
            return $this->redirectToRoute('program_index');
        }
         // Render the form
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
    * Getting a program by id
    *
    * @Route("/show/{id<^[0-9]+$>}", name="show")
    * @return Response
    */

    public function show(Program $program, SeasonRepository $seasonRepository): Response
    {
       
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    /**
     * @Route("/{programId}/seasons/{seasonId}", name="season_show")
     */
    public function showSeason(Program $program, Season $season): Response
    {

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }
}