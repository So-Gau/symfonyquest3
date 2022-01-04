<?php

// src/Controller/CategoryController.php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category")
 */
class CategoryController extends AbstractController

{
      /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/index.html.twig',
            ['category' => $category]
        );
    }
    /**
     * The controller for the category add form
     *
     * @Route("/new", name="new")
     */

    public function new(Request $request) : Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
         // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($category);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list
            return $this->redirectToRoute('category_new');
        }
         // Render the form
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{categoryName}", name="show")
     * * @return Response A response instance
     */

    public function show(string $categoryName, ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);
        
        if (!$category) {
            throw $this->createNotFoundException(
                'No program with name : '.$categoryName.' found in program\'s table.'
            );
        }

        $programs = $programRepository->findByCategory($category, ['id' => 'desc'], '3');
        
    return $this->render('category/show.html.twig', [
        'category' => $category, 'programs' => $programs
    ]);
    }



}
