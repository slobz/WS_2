<?php

// Include
require 'Libs/Slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

// Include BDD
require 'BDD/EntityManagerAcessor.php';
require 'Entity/Bibliotheque/Album.php';

use Entity\Bibliotheque\Album;


$app = new \Slim\Slim();
$app->config('debug', true);

//On recupère l'EM pour doctrine
$entityManager = BDD\EntityManagerAccessor::getEntityManager();

$app->get('/Album', function() use($app, $entityManager) {
    $repo = $entityManager->getRepository('Entity\Bibliotheque\Album');
    $albums = $repo->findAll();
    
    foreach ($albums as $album)
        $json[] = $album->toArray();
    
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($json);
});

$app->get('/Album/:id', function($id) use($app, $entityManager) {
    $entityManager = BDD\EntityManagerAccessor::getEntityManager();
    $repo = $entityManager->getRepository('Entity\Bibliotheque\Album');
    $album = $repo->find($id);
    $json = $album->toArray();
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($json);
});


$app->run();
