<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Comment;

use App\Form\ProgramType;
use App\Form\CommentType;
use App\Form\SearchProgramType;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\CommentRepository;

use App\Service\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


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
    public function index(Request $request, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(SearchProgramType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs =$programRepository->findLikeName($search);
        } else {
            $programs = $programRepository->findAll();
        }

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
        ]);
    }

     /**
     * The controller for the category add form
     *
     * @Route("/new", name="new")
     */

    public function new(Request $request, Slugify $slugify) : Response
    {
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
         // Get data from HTTP request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Program Object
            $entityManager->persist($program);
            $entityManager->flush();
            // Finally redirect to program list
            $this->addFlash('success', 'Un nouveau programme a bien été crée');
            
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
        
    }

    
    /**
     *
     * @Route("/{slug}", name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug" : "slug"}})
     * @return Response
     */
    public function show(Program $program, SeasonRepository $seasonRepository): Response
    {
        
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }
    
    #[Route('/{slug}/edit', name: 'program_edit')]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}/season/{seasonId}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     */
    public function showSeason(Program $program, Season $season): Response
    {

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }
                                                                                                                
    /**
     * @Route("/{slug}/season/{seasonId}/episode/{episodeId}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug" : "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId" : "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
     */
    //options={"mapping": {"episodeId": "id"}}) !rappel: url a droite / l'entité à gauche

    public function showEpisode(Request $request, Program $program, Season $season, Episode $episode,EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setEpisode($episode);
            $comment->setAuthor($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();
            
            return $this->redirectToRoute('program_episode_show', [
                'slug' => $program->getSlug(),
                'seasonId' => $season->getId(),
                'episodeId' => $episode->getId()
            ]);
        }
        
        return $this->renderForm('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'comments' => $commentRepository->findByEpisode($episode, ['id' => 'asc']),
            'form' => $form,
        ]);
    }
}

