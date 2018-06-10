<?php

// Namespaces
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// home
$app
    ->get(
        '/',
        function(Request $request, Response $response)
        {
            if(isset($_GET['search']) AND !empty($_GET['search'])) {
                $search = htmlspecialchars($_GET['search']);
                $query = "SELECT * FROM pokemons WHERE name LIKE '%".$search."%' LIMIT 300 ";
            }
            else {
                $query = "SELECT * FROM pokemons LIMIT 300 ";
            }
         // Fetch pokemon
         $prepare = $this->db->prepare($query);
         $prepare->execute();
         $pokemons = $prepare->fetchAll();
            // Data view
            $dataView = [
                'pokemons' => $pokemons,
            ];

            // Render
            return $this->view->render($response, 'pages/home.twig', $dataView);
        }
    )
    ->setName('home')
;

// description
$app
    ->get(
        '/description/{slug:[a-zA-Z0-9_-]+}',
        function(Request $request, Response $response, $arguments)
        {
            // Fetch pokemon
            $prepare = $this->db->prepare('SELECT pokemons.*, types.name AS name_type
            FROM pokemons
            INNER JOIN pokemons_types ON pokemons.id = pokemons_types.id_pokemon
            INNER JOIN types ON types.id = pokemons_types.id_type
            WHERE pokemons.name = :slug');
            $prepare->bindValue('slug', $arguments['slug']);
            $prepare->execute();
            $dataPokemon = $prepare->fetch();

          // Fetch students
          $prepare = $this->db->prepare('SELECT slug FROM pokemons  WHERE slug = :slug');
          $prepare->bindValue('slug', $dataPokemon->slug);
          $prepare->execute();
          $slug = $prepare->fetchAll();

            // View data
            $dataView = [
                'dataPokemon' => $dataPokemon,
                'slug' => $slug
            ];

            // Render
            return $this->view->render($response, 'pages/description.twig', $dataView);
        }
    )
    ->setName('description')
;

// 404
$container['notFoundHandler'] = function($container)
{
    return function($request, $response) use ($container)
    {
        return $container['view']->render($response->withStatus(404), 'pages/404.twig');
    };
};