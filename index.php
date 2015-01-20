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

// GET tous les albums
$app->get('/Album', function() use($app, $entityManager) {
    
    $repo = $entityManager->getRepository('Entity\Bibliotheque\Album');
    $albums = $repo->findAll();
    
    foreach ($albums as $album)
        $json[] = $album->toArray();
    
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($json);
});

// GET un seul album
$app->get('/Album/:id', function($id) use($app, $entityManager) {
    
    $repo = $entityManager->getRepository('Entity\Bibliotheque\Album');
    $album = $repo->find($id);
    
    if(empty($album)){
        $json = array("status" => false,"message" => "Album $id n'existe pas");
    }else{
        $json = $album->toArray();
        $app->response()->header("Content-Type", "application/json");
    }
    echo json_encode($json);
});

// POST Ajout d'un album
$app->post('/Album',function() use ($app, $entityManager) {
    
    $app->response()->header("Content-Type", "application/json");
    $album = new Album();
    $album->loadFromPost($app->request()->post());
    $entityManager->persist($album);
    $entityManager->flush();
    
    echo json_encode(array("status" => true));
});

// Mise à jour d'un album
$app->post('/Album/:id', function($id) use($app, $entityManager){
 
    $app->response()->header("Content-type", "application/json");
    $repo = $entityManager->getRepository('Entity\Bibliotheque\Album');
    $album = $repo->find($id);
    
    if($album){
        $album->loadFromPost($app->request()->post());
         $entityManager->flush();
        $json = array("status" => true);
    }else{
        $json = array("status" => false,"message" => "Album $id n'existe pas");
    }
    
    echo json_encode($json);
    
});


$app->run();
