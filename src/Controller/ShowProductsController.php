<?php

namespace App\Controller;

use App\Service\DataStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowProductsController extends AbstractController
{
    public function __construct(
        protected DataStorage $dataStorage
    )
    {
    }

    #[Route('/', name: 'app_show_products')]
    public function index(): Response
    {
        $data = $this->dataStorage->getData();

        $ugliest = $data[$this->dataStorage->getMaxSentimentKey('neg')];
        $nicest = $data[$this->dataStorage->getMaxSentimentKey('pos')];

        return $this->render(
            'index.html.twig', [
                'ugliest' => $ugliest,
                'nicest' => $nicest,
            ]
        );
    }
}
