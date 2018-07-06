<?php
namespace SD\AppclientloginBundle\Security;

use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\util\PasswordUpdaterInterface;
use FOS\UserBundle\util\CanonicalFieldsUpdater;
use Psr\Log\LoggerInterface;
use SD\AppclientloginBundle\Entity\User;
use GuzzleHttp\Client  as GuzzleHttpClient;


class UserManager extends BaseUserManager {
    private $class;
    
    /**
     * @var string
     */
    private $userClass;
    
    
    private $userRepository;
 
    private $userManager;
    private $user;
     
    private $client;
    
    private $logger;
    
    public function __construct(PasswordUpdaterInterface $passwordUpdater, 
                                CanonicalFieldsUpdater $canonicalFieldsUpdater, 
                                $userRepository,
                                $userClass,
                                LoggerInterface $logger) {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater);
    
        $this->logger = $logger;
        $this->userClass = $userClass;
        $this->userRepository = $userRepository;
    }
    
    /* public function createUser() {
        $class = $this->getClass();
        $user = new User();

        return $user;
    }*/
    
    public function deleteUser(UserInterface $user) {
        return true;
    }
    
    public function findUserBy(array $criteria) {
        
        $this->logger->info("findUserBy");
        echo 'UserManager::findUserBy';
        
        $this->user = $this->createUser();
        $this->client = new GuzzleHttpClient();
        $request = 'http://www.snowyday.dev/web/app_dev.php/users/';
        $request2 = $request.$criteria['id'];
        $response = $this->client->request('GET', $request2);
        
        $apiResponse = $response->getBody()->getContents();
       
        $apiResponse = json_decode($apiResponse, true);
         foreach ($apiResponse as $key => $value) {
            
            if ($key == 'username') {
                $this->user->setUsername($value);
            }
            else if ($key == 'usernameCanonical') {
                $this->user->setUsernameCanonical($value);
            }
            else if ($key == 'email') {
                $this->user->setEmail($value);
            }
            else if ($key == 'emailCanonical') {
                $this->user->setEmailCanonical($value);
            }
            else if ($key == 'password') {
                $this->user->setPassword($value);
                //$this->user->setPassword('ok');
            }
             else if ($key == 'plainPassword') {
                $this->user->setPlainPassword('ok');
            }
            else if  ($key == 'id') {
                $this->user->setId($value);
            }            
        }        
        
        $this->user->setEnabled(true);
        
        return $this->user;
    }
    
    public function findUserByUsername($username) {
               
        //$this->$userManager = this->get('fos_user.user_manager');
        $this->user = $this->createUser();
        $this->client = new GuzzleHttpClient();
        
        //$response = $this->client->request('GET', 'https://snowydayclient.dev/web/app_dev.php/users');
        
        //$resource = fopen('/home/ubuntu/workspace/test.txt', 'w');
        //$response = $this->client->request('GET', 'https://snowydayclient.dev/web/app_dev.php/users', ['debug' => true,
        //                                                                                                   'decode_content' => false,
        //                                                                                                    'save_to' => $resource]);
        //$filename = "/home/ubuntu/workspace/test.txt";
        //$handle = fopen($filename, "r");
        //$contents = fread($handle, filesize($filename));
        //fclose($handle);       
        
        //$response = $this->client->request('GET', 'http://www.snowyday.dev/web/app_dev.php/users');
        
        $request = 'http://www.snowyday.dev/web/app_dev.php/users/';
        $userinfos =  $username;
        $request3 = '/username';
        
        $request2 = $request.$userinfos.$request3;
        $this->logger->info("UserManager::findUserByUsername");
        $response = $this->client->request('GET', $request2);
        
        $this->logger->info("UserManager::findUserByUsername2");
        echo 'UserManager::findUserByUsername';
        $this->logger->info($username);
        //$this->logger->info($response->getBody()->getContents());
         //$this->logger->info($response->getHeader('content-type'));
        $code = $response->getStatusCode();
        $this->logger->info($code);
        

        // Il faut plutot utiliser un deserializer mais pour l'instant j'ai une erreur de syntax si 
        // j'utlise la fonction decode ou deserialize (qui appel json_decode au final) pourtant avec 
        // la fonction json_decode il ne semble pas y avoir de problème
        //$serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));
        //$result = $serializer->decode($response->getBody()->getContents(), 'json');
        //$items  = $serializer->denormalize($result);
        //OR
        //$this->user = $serializer->deserialize($json, 'SD\AppclientloginBundle\Entity\User', 'json');
          //$apiResponse = '[{"id":0,"username":"yasmany","username_canonical":"yasmany","email":"yasmanycm@gmail.com","email_canonical":"yasmanycm@gmail.com","enabled":false,"password":"ok","roles":[]}]';
          //$this->logger->info($apiResponse);
       
          $apiResponse = $response->getBody()->getContents();
       
          $apiResponse = json_decode($apiResponse, true);

		//$this->logger->info($apiResponse);
		  //$apiResponse = json_decode($response->getBody()->getContents(), true);
        
        $error = json_last_error_msg();
        $this->logger->info($error);
        
        //print_r($apiResponse);
        //$this->logger->info("TEST1");
        //$this->logger->info($apiResponse);
        //$this->logger->info("TEST2");
        
       // if (JSON_ERROR_NONE !== $this->lastError = json_last_error()) {
        //        throw new UnexpectedValueException(json_last_error_msg());
        //}
       
        
        foreach($apiResponse as $key => $value) {
            if ($key == 'username') {
                $this->user->setUsername($value);
            }
            else if ($key == 'usernameCanonical') {
                $this->user->setUsernameCanonical($value);
            }
            else if ($key == 'email') {
                $this->user->setEmail($value);
            }
            else if ($key == 'emailCanonical') {
                $this->user->setEmailCanonical($value);
            }
            else if ($key == 'password') {
                $this->user->setPassword($value);
                $this->logger->info($value);
                //$this->user->setPassword('ok');
            }
             else if ($key == 'plainPassword') {
                $this->user->setPlainPassword('ok');
            }
            else if  ($key == 'id') {
                $this->user->setId($value);
                $id = $this->user->getId();
            }
        }
        
        $this->user->setEnabled(true);
       
        //$this->updateUser($this->user);
        
        return $this->user;
    }
    
    public function findUserByEmail($email) {
        $this->logger->info("findUserByEmail");
        echo 'UserManager::findUserByEmail';
         return $this->user;
    }
    
    public function findUserByUsernameOrEmail($usernameOrEmail) {
        echo 'UserManager::findUserByUsernameOrEmail';
        $this->logger->info("findUserByUsernameOrEmail");
         return $this->user;
    }
    
    public function findUsers() {
        $this->logger->info("findUsers");
         echo 'UserManager::findUsers';
         return $this->user;
    }
    
     
    public function getClass() {
        //$class = 'User';
        
        //return $this->class;
        $this->logger->info("getClass");
        echo 'UserManager::getClass';
         return $this->userClass;
    }
    
    public function reloadUser(UserInterface $user) {
         $this->logger->info("reloadUser");
        echo 'UserManager::reloadUser';
         return $this->user;
    }
    
    /**
     * Updates a user.
     *
     * @param UserInterface $user
     * @param bool $andFlush
     *
     * @return void
     */
    public function updateUser(UserInterface $user) {
        $this->logger->info("UserManager::updateUser");
        
        $username = $user->getusername();
        $this->logger->info($username);
        $email    = $user->getEmail();
        $password = $user->getPassword();
        $plainpassword = $user->getPlainPassword();
        $this->logger->info("UserManager::updateUser2");
        $this->client = new GuzzleHttpClient();
        $this->logger->info("UserManager::updateUser3");
        $this->logger->info($username);
        $this->logger->info($email);
        $this->logger->info("password");
        $this->logger->info($password);
        $this->logger->info("UserManager::updateUser4");
        
        if (!$password) {
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $password);
            $user->setPassword($encoded);
            $this->logger->info("passwordencoded");
            $this->logger->info($password);
        }
        /* L'option json permet de formater au format json */
        /* L'option user indique le nom de la form utilisé au final qui pour   */
        /* nous sur le serveur correspond à UserType si j'ai bien tout conpris */
        $this->client->post(
            'http://www.snowyday.dev/web/app_dev.php/users',
            ['json' => array(
                  'user' => array(
                    'username' => $username,
                    'email'    => $email,
                    'password' => $password
                )
            )]
            );
         //$this->updateCanonicalFields($user);
         // api->update

    }
    
    
    /*public function updateCanonicalFields(UserInterface $user) {
        
        return true;
    }*/
    
   /* public function updatePassword(UserInterface $user) {
        
        return true;
    }*/
}