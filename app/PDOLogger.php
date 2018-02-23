<?php
/**
 * PDOLogger.
 *
 * Default database logger for Monolog/Monolog.
 *
 * @package    Loggers
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author     Andre Board <dre.board@gmail.com>
 * @link       http://www.code.dearneighbor.com
 * @copyright  Copyright &copy; 2017
 * @version    Version: 1.0.0
 * @access     public
 */
namespace App\Main;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use PDO;

/**
 * Class PDOLogger
 * @package App\Main
 */
class PDOLogger extends AbstractProcessingHandler
{
    /**
     * @var bool
     */
    private $initialized = false;
    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var
     */
    private $statement;

    /**
     * PDOLogger constructor.
     *
     * @param PDO $pdo
     * @param int $level
     * @param bool $bubble
     */
    public function __construct(PDO $pdo, $level = Logger::DEBUG, $bubble = true)
    {
        $this->pdo = $pdo;
        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        $this->statement->execute(array(
            'channel' => $record['channel'],
            'level' => $record['level'],
            'message' => $record['formatted'],
            'time' => $record['datetime']->format('U'),
        ));
    }

    /**
     * Create table logging table
     * @return void
     */
    private function initialize()
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS monolog '
            .'(channel VARCHAR(255), level INTEGER, message LONGTEXT, time INTEGER UNSIGNED)'
        );
        $this->statement = $this->pdo->prepare(
            'INSERT INTO monolog (channel, level, message, time) VALUES (:channel, :level, :message, :time)'
        );

        $this->initialized = true;
    }
}