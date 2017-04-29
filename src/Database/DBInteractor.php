<?php

namespace Uno\Database;

use PDO;
use PDOException;
use Uno\Contracts\DBEngine;
use Illuminate\Database\Capsule\Manager as Capsule;

abstract class DBInteractor implements DBEngine {

    protected $db;

    function __construct($config = null)
    {
        $this->db = $this->connect($config);
    }

    public function connect($config = null)
    {
        $config = is_null($config) ? config('database') : $config;

        return ($config['engine'] == 'illuminate')
            ? $this->useIlluminateDatabase($config)
            : $this->usePDODatabase($config);
    }

    /**
     * @param $config
     *
     * @return PDO|string
     */
    protected function usePDODatabase($config)
    {
        $dsn = $config['driver'] . ":";

        $dsn .= ($config['driver'] == 'sqlite')
            ? $config['path'] . ";"
            : "host=" . $config['host'] . ";dbname=" . $config['database'] . ";";

        try {
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch ( PDOException $e ) {
            return $e->getMessage();
        }
    }

    private function useIlluminateDatabase($config)
    {
        $capsule = new Capsule;

        $capsule->addConnection($config);

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();

        return $capsule;
    }

    protected function executeQuery($sql)
    {
        try {
            $sth = $this->db->query($sql);

            $sth->setFetchMode(PDO::FETCH_ASSOC);

            return $sth->fetchAll();
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    protected function executeAction($sql, $data = null)
    {
        try {
            $sth = $this->db->prepare($sql);

            ($data === null) ? $sth->execute() : $sth->execute($data);

            return true;
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}