<?php

namespace App\Controller;

use App\Entity\Titi;
use App\Form\TitiType;
use App\Repository\TitiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/titi")
 */
class TitiController extends AbstractController
{
    /**
     * @Route("/", name="titi_index", methods={"GET"})
     */
    public function index(TitiRepository $titiRepository): Response
    {
        return $this->render('titi/index.html.twig', [
            'titis' => $titiRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="titi_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $titi = new Titi();
        $form = $this->createForm(TitiType::class, $titi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($titi);
            $entityManager->flush();

            return $this->redirectToRoute('titi_index');
        }

        return $this->render('titi/new.html.twig', [
            'titi' => $titi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="titi_show", methods={"GET"})
     */
    public function show(Titi $titi): Response
    {
        return $this->render('titi/show.html.twig', [
            'titi' => $titi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="titi_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Titi $titi): Response
    {
        $form = $this->createForm(TitiType::class, $titi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('titi_index');
        }

        return $this->render('titi/edit.html.twig', [
            'titi' => $titi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="titi_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Titi $titi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$titi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($titi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('titi_index');
    }
}
