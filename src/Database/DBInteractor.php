<?php

namespace Uno\Database;

use PDO;
use PDOException;

abstract class DBInteractor {

    protected $db;

    function __construct($config = null)
    {
        $this->db = $this->connect($config);
    }

    protected function connect($config = null)
    {
        $config = is_null($config) ? config('database') : $config;

        $dsn = $config['driver'] . ":";

        $dsn .= ($config['driver'] == 'sqlite')
            ? $config['path'] . ";"
            : "host=". $config['host'] . ";dbname=" . $config['database'] . ";";

        try {
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
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