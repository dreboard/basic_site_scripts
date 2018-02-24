<?php
/**
 * SaveSession Class.
 * Default session handler for the application.
 * <p>Sets custom session functions and prevents
 * unexpected effects when using objects
 * as save handlers.<p>
 * Stores session data in a MySQL database rather than files
 */

namespace App\Main;

use DebugBar\StandardDebugBar;
use PDO;
use PDOException;
use Throwable;
use SessionHandlerInterface;
use App\Main\SessionErrorException;

/**
 * Class SaveSession
 * @package App\Main
 */
class SaveSession implements SessionHandlerInterface
{

    /**
     * @var object PDO
     */
    private $pdo;

    /**
     * @var object StandardDebugBar
     */
    protected $debugBar;

    /**
     * @var int SESSION_LIMIT
     */
    private const SESSION_LIMIT = 86400;

    /**
     * SaveSession constructor.
     * @param PDO $pdo
     * @param StandardDebugBar $debugBar
     */
    public function __construct(Database $pdo, StandardDebugBar $debugBar)
    {
        $this->pdo = $pdo;
        $this->debugBar = $debugBar;
    }

    /**
     * Initialize session
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool|null <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function open($save_path, $session_name): ?bool
    {
        $query = "CALL SessionDeleteExpired(:limit)";
        $limit = (new \DateTime())->getTimestamp() - self::SESSION_LIMIT;
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 10);
        return $stmt->execute();
    }

    /**
     * Start Session
     * <p>This function will be called every time you
     * want to start a new session and perform the
     * following options
     * <ul>
     * <li>Make sure the session cookie is not accessible</li>
     * <li>Check if hash is available</li>
     * <li>Force the session to only use cookies</li>
     * <li>Get and set session cookie parameters</li>
     * <li>Change the session name</li>
     * <li>start the session</li>
     * <li>regenerates the session and delete the old one</li>
     * <li>generates a new encryption key</li>
     *
     * @param $session_name
     * @param $secure
     */
    public static function start_session($session_name, $secure)
    {
        die(__METHOD__);
        try {
            $httponly = true;
            $session_hash = Security::key_algo;

            if (in_array($session_hash, hash_algos())) {
                ini_set('session.hash_function', $session_hash);
            }
            ini_set('session.hash_bits_per_character', 5);
            ini_set('session.use_only_cookies', 1);

            $cookieParams = session_get_cookie_params();
            session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
            session_name($session_name);
            session_start();
            session_regenerate_id(true);
        } catch (Throwable $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

    }


    /**
     * Close the session
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     */
    public function close(): bool
    {
        $this->pdo = null;
        return true;
    }

    /**
     * Returns an encoded string of the read data.
     * @param string $id The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     */
    public function read($id)
    {
        try {
            $query = "CALL SessionReadData(:id)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 32);
            $stmt->execute();
            $session = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($session) {
                $ret = $session['data'];
            } else {
                $ret = false;
            }
            return (string)$ret;
        } catch (PDOException | Throwable $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            if (\defined('ENVIRONMENT') && ENVIRONMENT === 'development'){
                $this->debugBar['exceptions']->addException($e);
            }
        }
    }

    /**
     * Write Session Data.
     *
     * <p>This function is used when we assign a value to a session.
     * The function encrypts all the data which gets inserted into the database.</p>
     *
     * @param string $id
     * @param string $data
     * @return bool
     */
    public function write($id, $data): bool
    {
        try {
            $query = 'CALL SessionWriteData(:id, :time, :data)';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 32);
            $stmt->bindParam(':data', $data, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
            $stmt->bindValue(
                ':time',
                time() + ini_get('session.gc_maxlifetime'),
                PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT
            );
            return $stmt->execute();
        } catch (PDOException | Throwable $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            if (\defined('ENVIRONMENT') && ENVIRONMENT === 'development'){
                $this->debugBar['exceptions']->addException($e);
            }
        }
    }

    /**
     * Destroy a session by id
     * @param string $id The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     */
    public function destroy($id): bool
    {
        try {
            $query = 'CALL SessionDestroy(:id)';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 128);
            return $stmt->execute();
        } catch (Throwable $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            if (\defined('ENVIRONMENT') && ENVIRONMENT === 'development'){
                $this->debugBar['exceptions']->addException($e);
            }
        }
    }

    /**
     * Session garbage collection
     * @param int $max
     * @return bool
     */
    public function gc($max): bool
    {
        try {
            $old = time() - $max;
            $stmt = $this->pdo->prepare("CALL SessionGarbageCollect(:old)");
            $stmt->bindParam(':old', $old, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 10);
            return $stmt->execute();
        } catch (Throwable $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            if (\defined('ENVIRONMENT') && ENVIRONMENT === 'development'){
                $this->debugBar['exceptions']->addException($e);
            }
        }
    }


    /**
     *
     */
    public function logout($id)
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - self::SESSION_LIMIT, $params['path'], $params['domain'], $params['secure'], $params["httponly"]);
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}