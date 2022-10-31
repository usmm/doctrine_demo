<?php
// bootstrap.php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Attributes
/*$config = ORMSetup::createAttributeMetadataConfiguration(
     array(__DIR__."/src"),
     true
);*/
// or if you prefer annotation, YAML or XML
// $config = ORMSetup::createAnnotationMetadataConfiguration(
//    paths: array(__DIR__."/src"),
//    isDevMode: true,
// );
 $config = ORMSetup::createXMLMetadataConfiguration(
     array(__DIR__."/config/xml"),
    true
);
// $config = ORMSetup::createYAMLMetadataConfiguration(
//    paths: array(__DIR__."/config/yaml"),
//    isDevMode: true,
// );

// database configuration parameters
//example
/*$connectionParams = [
    'dbname' => 'mydb',
    'user' => 'user',
    'password' => 'secret',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
];*/

require_once 'include/dbconn.php';

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);

var_dump($entityManager);