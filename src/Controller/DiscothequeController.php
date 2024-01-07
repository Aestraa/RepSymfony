<?php

namespace App\Controller;

use App\Entity\Chanson;
use App\Entity\Artiste;
use App\Entity\Type;
use App\Form\ChansonType;
use App\Repository\ChansonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArtisteRepository;

class DiscothequeController extends AbstractController
{
    #[Route('/discotheque', name: 'app_discotheque')]
    public function index(ChansonRepository $chansonRepository): Response
    {
        return $this->render('discotheque/index.html.twig', [
            'chansons' => $chansonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chanson_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChansonRepository $chansonRepository): Response
    {
        $chanson = new Chanson();
        $form = $this->createForm(ChansonType::class, $chanson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chansonRepository->save($chanson, true);

            $this->addFlash('notice', 'Article ajouté avec succès !');

            return $this->redirectToRoute('app_discotheque', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('discotheque/new.html.twig', [
            'chanson' => $chanson,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chanson_show', methods: ['GET'])]
    public function show(Chanson $chanson): Response
    {
        return $this->render('discotheque/show.html.twig', [
            'chanson' => $chanson,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chanson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chanson $chanson, ChansonRepository $chansonRepository): Response
    {
        $form = $this->createForm(ChansonType::class, $chanson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chansonRepository->save($chanson, true);

            $this->addFlash('notice', 'Article modifié avec succès !');

            return $this->redirectToRoute('app_discotheque', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('discotheque/edit.html.twig', [
            'chanson' => $chanson,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chanson_delete', methods: ['POST'])]
    public function delete(Request $request, Chanson $chanson, ChansonRepository $chansonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $chanson->getId(), $request->request->get('_token'))) {
            $chansonRepository->remove($chanson, true);

            $this->addFlash('notice', 'Article supprimé avec succès !');
        }

        return $this->redirectToRoute('app_discotheque', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/artistes/type/{id}', name: 'app_artiste_by_type')]
    public function artistesParType(Type $type, ArtisteRepository $artisteRepository): Response
    {
        // Récupérer les artistes de ce type
        $artistes = $artisteRepository->findByType($type->getId());

        return $this->render('discotheque/artistes_par_type.html.twig', [
            'type' => $type,
            'artistes' => $artistes,
        ]);
    }

    #[Route('/artiste/{id}', name: 'app_artiste_details')]
    public function showArtiste(Artiste $artiste): Response
    {
        // Ici, $artiste est automatiquement récupéré par Symfony grâce au ParamConverter
        return $this->render('discotheque/artiste_details.html.twig', [
            'artiste' => $artiste,
        ]);
    }
}
