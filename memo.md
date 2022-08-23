# MVC from scratch


site liste les packages de composer
>https://packagist.org/

un vendor c est un editeur de paquet

faire un ``composer install`` pour recuperer le dossier vendor ignoré qui contient les dépendances.

composer va nos faire de l'autoloading pour charger les classes (il remplace les require_once)

App/src/    ds composer.json  autoload psr-4 pour autoloader les classes qui sont ds src/

donc qd on cree une classe => ``namespace App;`` au debut du code avant de creer la classe

