<?php
/**
 * Log in/out class.
 *
 * Log user in by checking database against
 * password supplied
 */

namespace App\Main;

use PDO;
use PDOException;
use Throwable;

/**
 * Class Login
 * @package App\Main
 */
class Login
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * Login constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->user = new User($this->db);
    }

    /**
     * Log user in
     * @param string $name
     * @param string $password
     * @throws \Exception
     */
    public function loginUser(string $name, string $password)
    {
        try{
            $user = $this->user->getUser($name, $password);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user']['name'] = $user['name'];
                $_SESSION['user']['id'] = $user['id'];
                header('Location: page2.php');
                exit();
            }
        } catch (Throwable $e){
        	error_log($e->getMessage());
        }
        header('Location: index.php');
        exit();
    }



    /**
     * Log user out
     */
    public function logout(): void
    {
        try {
            session_start();
            session_destroy();
            $_SESSION = [];
            header('Location: index.php');exit;
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
    }

}