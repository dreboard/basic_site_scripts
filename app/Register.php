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
 * Class Register
 * @package App\Main
 */
class Register
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * Register constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
	    $this->user = new User($this->db);
    }

    /**
     * Register new user
     * @param string $name
     * @param string $password
     * @throws \Exception
     */
    public function registerUser(string $name, string $password)
    {
        /*if(empty($name) || null === $name || false === $this->checkPassword($password)){
            throw new \Exception('Requirements not met');
            $_SESSION['msg'] = 'Requirements not met';
        }*/
        $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($this->user->checkExisting($name)){
            try{
                $sql = $this->db->prepare(
                    'CALL SaveUserToDB(:name, :pass, :level)'
                );
                $sql->bindParam(
                    ':name',
                    $name,
                    PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,
                    22
                );
                $sql->bindValue(
                    ':pass',
                    password_hash($password,PASSWORD_DEFAULT, ['cost' => 12]),
                    PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT
                );
                $sql->bindValue(
                    ':level',
                    1,
                    PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT
                );
                ;
                if($sql->execute()){
                    $this->userSession($name, $this->db->lastInsertId());
                    header('Location: page2.php');
                    exit();
                } else {
                    $_SESSION['msg'] = 'Did not save';
                }

            } catch(Throwable $e){
                error_log($e);
                $_SESSION['msg'] = $e->getMessage().$e->getLine();
                header('Location: index.php');
                exit();
            }
        }
    }



    /**
     * @param string $name
     * @param int $id
     */
    protected function userSession(string $name, $id): void
    {
        $_SESSION['user']['name'] = htmlspecialchars($name, ENT_QUOTES, 'utf-8');
        $_SESSION['user']['id'] = (int)$id;
    }

    /**
     * Check Password length
     * @param string $pass
     * @return bool
     */
    protected function checkPassword(string $pass):bool {
        $num = strlen($pass);
        return $num < 5 || $num > 20;
    }
}
