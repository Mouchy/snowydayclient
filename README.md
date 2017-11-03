api.snowyday.org
================

A Symfony project created on October 2, 2017, 12:01 pm.



Snowyday
########

https://victor.4devs.io/en/symfony2/how-to-use-a-custom-storage-layer-in-FOSUserBundle.html

Symfony commands
****************
Vide le cache
php bin/console cache:clear --no-warmup --env=dev

Affiche le paramétrage correspondant à FOS_REST
php bin/console debug:config fos_rest

Creation d'un utilisateur fos_user
php bin/console fos:user:create

Creation de la table correspondant à la classe dans entity
php bin/console doctrine:schema:update --force

php bin/console debug:router

Fichiers modifiés
*****************
composer.json
//web/app_dev.php
//src/SD/AppserverloginBundle/Resources/views/Default/index.html.twig


CLOUD9-PHP7
***********
Par defaut sur cloud9 on est dans une version 5.x de PHP ::

php -v

pour se positionner en version 7 faire les commandes ci-dessous. Après ces commandes on n'aura plus accés à PHPMYADMIN. 
Tester la commande suivante pour rétablir phpmyadmin phpmyadmin-ctl install mais pour l'instant cela n'a pas marché cela m'a rajouté une ligne d'erreur dans la config apache donc pour l'instant ne pas faire. 
Je n'ai pas encore réussi à le réinstaller. ::

 sudo add-apt-repository ppa:ondrej/php 
 sudo apt-get update 
 sudo apt-get -y purge php5 libapache2-mod-php5 php5 php5-cli php5-common php5-curl php5-gd php5-imap php5-intl php5-json php5-mcrypt php5-mysql php5-pspell php5-readline php5-sqlite 
 sudo apt-get autoremove 
 sudo apt-get install php7.0 
 sudo apt-get install php7.0-mysql 
 
/* j'ai aussi trouvé la commande ci-dessous qui semble installer plus de package utile ou pas je ne sais pas encore a priori plus complete j'ai une erreur sur le serveur symfony sur Xmlutil qui a été résolu avec la commande ci-dessous */ 
sudo apt-get install php7.0-curl php7.0-cli php7.0-dev php7.0-gd php7.0-intl php7.0-mcrypt php7.0-json php7.0-mysql php7.0-opcache php7.0-bcmath php7.0-mbstring php7.0-soap php7.0-xml php7.0-zip -y

MySQl
*****

mysql-ctl start

Ajout de l'utilisateur manu comme super utilisateur ::

mysql -u root

GRANT ALL PRIVILEGES ON *.* TO manu@localhost IDENTIFIED BY 'xevrod2x' WITH GRANT OPTION;

Composer
********
pour installer composer je vais à l'adresse ci-dessous et je copie-colle les commandes dans une console.

https://getcomposer.org/download/

symfony
*******
On récupére le script symfony ::

php -r "readfile('https://symfony.com/installer');" > symfony

On lance l'installation du projet (peu importe le nom du projet on va le redéplacer à la racine par la suite) ::

php symfony new api.snowyday.org

Déplacer le projet créé à la racine ::

mv api.snowyday.org/{,.} ./ rm -rf api.snowyday.org

Il faut ensuite supprimer les lignes ci-dessous sinon on obtient une erreure en allant sur le site ::

//web/app_dev.php 
// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'], true) || PHP_SAPI === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

On peut ensuite se rendre à l'adresse ci-dessous pour vérifier que tout fonctionne normalement 
https://snowyday-man.c9users.io/web/app_dev.php

Bundle symfony
==============

Je vais maintenant créer un bundle afin de le réutiliser dans d'autres projets. Je vais créer un bundle de login de clients

php bin/console generate:bundle

Je reponds "yes" à la question de réutilisation du bundle puisque c'est évidement mon but 
Bundle namesapce: SD/AppclientloginBundle 
Bundle name: Enter (on laisse le defaut) 
target Directory : Enter (on laisse le defaut) 
Configuration format (annotation, yml, xml, php) [xml]: yml

Dans les derniéres version j'ai eu une erreur d'installation j'ai du modifier le fichier composer.json pour y ajouter la ligne de mon bundle ::

 "autoload": {
        "psr-4": {
            "AppBundle\\": "src/AppBundle",
            "SD\\AppclientloginBundle\\": "src/SD/AppclientloginBundle",
            "SD\\AppclientloginBundle\\Entity\\": "src/SD/AppclientloginBundle/Entity"
        },

puis j'ai fait la commande ::

 composer dump-autoload



Aprés ça le lien ci dessous pointe vers notre bundle et on perdu la barre du bas de deboguage.
https://snowyday-man.c9users.io/web/app_dev.php 
Symfony rajoute cette barre à chaque page contenant les balises .
On va donc modifier le fichier ci-dessous pour la récupérer.

{# src/SD/AppuserclientBundle/Resources/views/Default/index.html.twig #}
Hello World!


<html>
  <body>
    Hello World snowyday!
  </body>
</html>

Ajout GITHUB
************

Créer un nouveau repository sur GITHUB, noter le lien vers ce nouveau repository https://github.com/Mouchy/snowyday.git

Puis initialiser git sur cloud9 pour pointer sur le repository github, dans une console cloud9 ::

 git init
 git remote add origin https://github.com/Mouchy/snowyday.git

Voila pas besoin de plus il faut maintenant ajouter des fichiers dans notrerepository local puis les commiter en local ::

 git add README.md
 git commit -m "Test pour la mise en place de GIT"

Ensuite on va synchroniser notre repository local avec celui de github ::

 git push -u origin master

FOSUserBundle
*************

Download ::

 composer require friendsofsymfony/user-bundle "~2.0"
 
Enable the Bundle ::

 // app/AppKernel.php
 class AppKernel extends Kernel
 {
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new FOS\UserBundle\FOSUserBundle(),
        );

        // ...
    }
 }
 
Configuration ::

 //app/config/config.yml
 fos_user:
    #db_driver: orm # other valid values are 'mongodb' and 'couchdb'
    db_driver: custom
    firewall_name: main
    user_class: SD\AppuserclientBundle\Entity\User
    # si on utilise notre propre manager utilisateur ce que l'on fait en définissant le db_driver à custom il faut définir un user manager comme ci-dessous
    service: 
        user_manager: 'app.user_manager'
    #use_listener: false
    from_email:
        address: "NONO"
        sender_name: "NONO"
        
l'option db_driver à custom permet de se passer de l'orm doctrine et de passer par le notre.

Security ::
 
  //app/config/security.yml
 security:
    encoders:
        #Symfony\Component\Security\Core\User\User: plaintext
        FOS\UserBundle\Model\UserInterface: bcrypt
        
    providers:
        fos_userbundle:
            id: fos_user.user_provider.username

 app.repository.user:
        class: SD\AppclientloginBundle\Model\UserRepository
        
Service ::

  app.repository.user:
        class: SD\AppuserclientBundle\Model\UserRepository
    #service_name:
    #    class: AppBundle\Directory\ClassName
    #    arguments: ['@another_service_name', 'plain_value', '%parameter_name%']
    # on utilise notre propre user manager car on a défini db_driver à custom
    app.user_manager:
        class: SD\AppuserclientBundle\Security\UserManager
        arguments: ['@fos_user.util.password_updater', 
                    '@fos_user.util.canonical_fields_updater', 
                    '@app.repository.user', 
                    '%fos_user.model.user.class%',
                    '@logger']
                    
                    
                    


FOS\UserBundle\Security\UserProvider

    loadUserByUsername
    refreshUser
