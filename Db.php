<?php

final class Db
{
    private const HOST = 'your database host name';
    private const NAME = 'your database name';
    private const USERNAME = 'your database username';
    private const PASSWORD = 'your database password';

    private static $db = null;
    private static $state;


    public static function connect()
    {
        if (!self::$db) {
            try {
                self::$db = new \PDO('mysql:host=' . self::HOST . ';dbname=' . self::NAME . ';charset=utf8', self::USERNAME, self::PASSWORD);
                self::$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
                self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                exit("Unable to connect to Database! <br>" . $e->getMessage());
            }
        }
    }

    public static function disconnect()
    {
        self::$db = null;
    }

    /**
     * @param string $query
     * 
     * @return self
     */
    public static function prepare($query)
    {
        self::$state = self::$db->prepare($query);
        return new static;
    }

    /**
     * @param string|int $param 
     * 
     * @param mixed &$value
     * @param int $pdoParam
     * 
     * @return self
     */
    public static function bindParam($param, &$value, $pdoParam = null)
    {
        if ($pdoParam === null)
            self::$state->bindParam($param, $value);
        else
            self::$state->bindParam($param, $value, $pdoParam);
        return new static;
    }

    /**
     * @param string|int $param 
     * 
     * @param mixed $value
     * @param int $pdoParam
     * 
     * @return self
     */
    public static function bindValue($param, $value, $pdoParam = null)
    {
        if ($pdoParam === null)
            self::$state->bindValue($param, $value);
        else
            self::$state->bindValue($param, $value, $pdoParam);
        return new static;
    }


    /**
     * @param array|null $params array of params if params already haven't been bounded. 
     *
     * @return self
     */
    public static function execute(array $params = null)
    {
        if ($params) {
            return self::$state->execute($params);
        }
        return self::$state->execute();
    }

    /**
     * @param int $mode fetch mode. 
     * 
     * @return mixed fetched result
     */
    public static function fetch($mode = \PDO::FETCH_OBJ)
    {
        return self::$state->fetch($mode);
    }


    /**
     * @param int $mode fetch mode. 
     * 
     * @return mixed fetched results
     */
    public static function fetchAll($mode = \PDO::FETCH_OBJ)
    {
        return self::$state->fetchAll($mode);
    }

    /**
     * execute and fetch first result at same time (also bind params if params be passed). 
     * call when statement wasn't executed!
     * 
     * 
     * @param int $mode fetch mode. 
     * @param array|null $params array of params if params already haven't been bounded. 
     * 
     * @return mixed
     */
     
    public static function getFirst($mode = \PDO::FETCH_OBJ, array $params = null)
    {
        if (!self::execute($params)) {
            return false;
        }
        return self::$state->fetch($mode);
    }


    /**
     * execute and fetch results at same time (also bind params if params be passed). 
     * call when statement wasn't executed!
     * 
     * 
     * @param int $mode fetch mode. 
     * @param array|null $params array of params if params already haven't been bounded. 
     * 
     * @return mixed
     */
    public static function getAll($mode = \PDO::FETCH_OBJ, array $params = null)
    {
        if (!self::execute($params)) {
            return false;
        }
        return self::$state->fetchAll($mode);
    }
}
