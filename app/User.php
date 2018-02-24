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
use App\Main\Database;

/**
 * Class Login
 * @package App\Main
 */
class User
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * Login constructor.
     * @param PDO $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Log user in
     * @param string $name
     * @param string $password
     * @throws \Exception
     */
    public function userByID(int $id)
    {
        if(empty($id) || is_null($id)){
            return false;
        }
        try{
            $sql = $this->db->prepare('CALL FindUserByID(:id)');
            $sql->bindParam(':id', $id);
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException | Throwable $e){
            echo $e->getMessage();
        }
    }

	/**
	 * Find user
	 * @param string $name
	 * @return mixed
	 * @throws \Exception
	 */
	public function getUser(string $name)
	{
		if(empty($name) || is_null($name)){
			throw new \Exception('Username Required');
		}
		try{
			$sql = $this->db->prepare('CALL FindUser(:user)');
			$sql->bindValue(':user', $name);
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}catch (PDOException | Throwable $e){
			echo $e->getMessage();
		}
	}

	/**
	 * Find user
	 * @param string $name
	 * @return mixed
	 * @throws \Exception
	 */
	public function checkExisting(string $name)
	{
		$sql = $this->db->prepare('CALL FindUserByName(:user)');
		$sql->bindValue(':user', $name);
		$sql->execute();
		if($sql->rowCount() < 0){
			return false;
		}
		return true;
	}
}
