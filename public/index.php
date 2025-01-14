<?php

require_once __DIR__ . '/../vendor/autoload.php';




//empecher que les requetes soient en double avec la requete favicon suite redirection index.php
// si on est pas en ligne de commande (on viens du navigateur) et qu on charge le favicon (.png, .jpg....)
// alors return false donc pas de double requete de index.php (tout le script)
if (
  php_sapi_name() !== 'cli' && // Pas en mode ligne de commande
  preg_match('/\.(?:png|jpg|jpeg|gif|svg|ico)$/', $_SERVER['REQUEST_URI']) // extension = asset
) {
  return false;
} 


use App\Controller\IndexController;
use App\Controller\UserController;
use App\DependencyInjection\Container;
use App\Repository\UserRepository;
use App\Routing\RouteNotFoundException;
use App\Routing\Router;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


// **** ENV VARS *******************************************************
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env'); // DIR = dossier courant
// loadEnv va mapper le contenu du fichier .env puis .env.local ds la superglobale $_ENV
// *********************************************************************


// ****** DOCTRINE *****************************************************
$paths = ['src/Entity']; // chemin des fichiers entités = classes transformée en table de données
$isDevMode = $_ENV['APP_ENV'] === 'dev';


$dbParams = [
  'driver'   => $_ENV['DB_DRIVER'],
  'host'     => $_ENV['DB_HOST'],
  'port'     => $_ENV['DB_PORT'],
  'user'     => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASSWORD'],
  'dbname'   => $_ENV['DB_NAME']
];

$config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

$driver = new AttributeDriver($paths);
$entityManager->getConfiguration()->setMetadataDriverImpl($driver);
// **********************************************************************


// ******** TWIG ********************************************************
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
  'debug' => $_ENV['APP_ENV'] === 'dev',
  'cache' => __DIR__ . '/../var/cache/twig'
]); // ******************************************************************


// ***** REPOSITORIES ****************************************************
$userRepository = new UserRepository($entityManager);
// ***********************************************************************


// ***** CONTAINER *******************************************************
$container = new Container();
$container->set(EntityManager::class, $entityManager);
$container->set(Environment::class, $twig);
$container->set(UserRepository::class, $userRepository);
// **********************************************************************


// si on est en ligne de commande alors on fait juste un return donc pas de requete index.php
if (php_sapi_name() === 'cli') {
  return;
}

// ***** ROUTE *******************************************************
$router = new Router($container);
$router->registerRoutes();

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

try {
  $router->execute($requestUri, $requestMethod);
} catch (RouteNotFoundException $e) {
  http_response_code(404);
  echo "<p>Page non trouvée</p>";
  echo "<p>" . $e->getMessage() . "</p>";
} // *****************************************************************





// **** test insertion utilisateur a la mano ******************************



/*  use App\Entity\User;

$user = new User();

$user->setName("Toto")
  ->setFirstname("Ralou")
  ->setUsername("Toto RAlou")
  ->setPassword(password_hash('toto', PASSWORD_BCRYPT))
  ->setEmail("toto@nol.mg")
  ->setBirthDate(new DateTime('1961-05-06'));

$entityManager->persist($user); // enregistre (statement)

$entityManager->flush(); 

echo '<pre>';
var_dump($user);
echo '</pre>'; 

*/ // execute pour envoyer plusieurs requetes (gros statement) en même temps



// **** fin test insertion utilisateur a la mano ****************************

