<?php
//src/AppclientloginBundle/Model/UserRepository.php
namespace SD\AppclientloginBundle\Model;

use GuzzleHttp\Client  as GuzzleHttpClient;
 
// "use" statements
 
class UserRepository 
{
  
    private $client;
    private $user;
    private $userManager;
    
    /**
     * 
     * 
     */
    public function __construct()
    {
         $this->client = new GuzzleHttpClient();
         
    }
 
    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function getByUsername($username)
    {
        
        
       // $response = $this->client->get('https://snowyday-man.c9users.io/web/app_dev.php/users', ['user' =>['username' => 'yasmany',
        //                                                                                             'password' => 'ok']]);
        //$apiResponse = json_decode($response->getBody()->getContents());
        
        //$response = [];
        //$response = $apiResponse;
      
        //$this->user->setUsername($response->username);
        //$this->user->setPassword($response->username);
        $this->userManager = $container->get('fos_user.user_manager');
        $user        = $this->$userManager->createUser();
        
        echo 'UserRepository::getByUsername';
        $user->setUsername('yasmany');
        $user->setPassword('ok');
        $user->setEmail('john.doe@example.com');
       
        return $user;
    }
 
    /**
     * @param string $username
     *
     * @return bool
     */
    public function deleteByUsername($username)
    {
        return $this->api->deleteUser($username);
    }
 
    /**
     * @param UserInterface $user 
     */
    public function updateUser(UserInterface $user)
    {
        // api->update 
    }
 
    // other methods like: getAllUsers, findBy, findOneBy and so on
}