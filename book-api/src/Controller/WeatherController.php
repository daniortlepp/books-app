<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/api/weather', name: 'weather', methods: ['GET'])]
    public function getWeather(Request $request): JsonResponse
    {
        $city = $request->query->get('city', 'Sao Paulo');
        $apiKey = 'cea39126';

        $response = $this->httpClient->request('GET', 'https://api.hgbrasil.com/weather', [
            'query' => [
                'format' => 'json_cors',
                'key' => $apiKey,
                'city_name' => $city,
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            return new JsonResponse($response->toArray());
        } else {
            return new JsonResponse([
                'error' => 'Não foi possível obter os dados do clima'
            ], 500);
        }
    }
}
