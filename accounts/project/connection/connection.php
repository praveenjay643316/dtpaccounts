<?php

/**
 * Trait ConnectionClass
 *
 * Used to PDO Connection , DDL & DML Function
 *
 */
trait ConnectionClass
{

    // **********************************************DATABASE CONNECTION***********************************************

    /**
     * db_connect
     *
     * @return PDO Object Database Connected PDO Object
     */
    public function db_connect()
    {
        if (!isset($this->db)) {
            try {

                $db_data = $this->dbData();

                $db = new PDO($db_data->dbserver . ':host=' . $db_data->dbhost . ';dbname=' . $db_data->dbname . ';port=' . $db_data->dbport, $db_data->dbuser, $db_data->dbpass);

                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
            } catch (PDOException $e) {
                echo 'Connection failed: ';// . $e->getMessage();
            }
        } else {
            $db = $this->db;
        }

        return $db;
    }

    /**
     * beginTransaction
     *
     * Transaction Begin
     *
     * @return void
     */
    public function beginTransaction()
    {
        $this->db = $this->db_connect();
        try {
            $this->db->beginTransaction();
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    }

    /**
     * commit
     *
     * Transaction End
     *
     * @return void
     */
    public function commit()
    {
        $this->db = $this->db_connect();
        try {
            $this->db->commit();
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * rollBack
     *
     * Transaction Rollback
     *
     * @return void
     */
    public function rollBack()
    {
        $this->db = $this->db_connect();
        try {
            $this->db->rollBack();
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * Replaces any parameter placeholders in a query with the value of that
     * parameter.
     * Useful for debugging. Assumes anonymous parameters from
     * $params are are in the same order as specified in $query
     *
     * @param string $query
     *            The sql query with parameter placeholders
     * @param array $params
     *            The array of substitution parameters
     * @return string The interpolated query
     */
    public function interpolateQuery($query = "", $params = array())
    {
        $keys = array();
        $values = $params;

        # build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            }

            if (is_array($value)) {
                $values[$key] = "'" . implode("','", $value) . "'";
            }

            if (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }

        $query = preg_replace($keys, $values, $query);

        return $query;
    }

    /**
     * prepare
     *
     * @param  String $qry_string
     * @param  array $params
     * @param  integer $return_array_type
     *  1 - fetchAll
     *  2 - fetchAll(FETCH_ASSOC)
     *  3 - fetchAll(FETCH_NUM)
     *  4 - fetch(FETCH_ASSOC)
     *  5 - fetch(FETCH_NUM)
     *  6 - debugDumpParams
     *  7 - interpolateQuery and exit
     *  8 - debugDumpParams and exit
     *  9 - PDOStatement Object
     * @return array|Object
     * @throws PDOException Error Object
     */
    public function prepare($qry_string="", $params = array(), $return_array_type = 1)
    {
        $this->db = $this->db_connect();

        try {
            switch ($return_array_type) {

                case 1:
                    $sth = $this->db->prepare($qry_string, array(
                        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
                    ));
                    $sth->execute($params);
                    $getres = $sth->fetchAll();
                    break;
                case 2:
                    $sth = $this->db->prepare($qry_string, array(
                        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
                    ));
                    $sth->execute($params);
                    $getres = $sth->fetchAll(PDO::FETCH_ASSOC);
                    break;
                case 3:
                    $sth = $this->db->prepare($qry_string, array(
                        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
                    ));
                    $sth->execute($params);
                    if ($sth->rowCount() != 0) {
                        $getres = $sth->fetchAll(PDO::FETCH_NUM);
                    } else {
                        $getres = array();
                    }
                    break;
                case 4:
                    $sth = $this->db->prepare($qry_string, array(
                        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
                    ));
                    $sth->execute($params);
                    if ($sth->rowCount() != 0) {
                        $getres = $sth->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $getres = array();
                    }
                    break;
                case 5:
                    $sth = $this->db->prepare($qry_string, array(
                        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
                    ));
                    $sth->execute($params);
                    $getres = $sth->fetch(PDO::FETCH_NUM);
                    break;
                case 6:
                    $sth = $this->db->prepare($qry_string, array(
                        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
                    ));
                    $sth->execute($params);
                    ob_start();
                    $sth->debugDumpParams();
                    $getres = ob_get_contents();
                    ob_end_clean();
                    break;
                case 7:
                    echo '<pre>'.$getres = $this->interpolateQuery($qry_string, $params);
                    exit();
                    break;
                case 8:
                    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                    $sth = $this->db->prepare($qry_string, array(
                        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
                    ));
                    $sth->execute($params);
                    ob_start();
                    $sth->debugDumpParams();
                    $getres = ob_get_contents();
                    ob_end_clean();
                    echo htmlentities($getres);
                    exit();
                    break;
                case 9:
                    $sth = $this->db->prepare($qry_string, array(
                        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
                    ));
                    $getres = $sth->execute($params);

                    break;
                case 102:
                    $sth = $this->db->prepare($qry_string, array(
                        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
                    ));
                    $sth->execute($params);
                    $getres = $sth->fetchAll(PDO::FETCH_ASSOC);
                    
                        echo '<pre>'.$getres = $this->interpolateQuery($qry_string, $params);
                        //exit();
                        break;
            }
            return $getres;
        } catch (PDOException $e) {
			//var_dump($e);
            return $e;
        }
    }

    /**
     * prepareStatus
     *
     * @param  mixed $prepare_object  array or PDOException
     * @return boolean
     */
    public function prepareStatus($prepare_object = "")
    {
        if (gettype($prepare_object) == "array") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * makePrepareArrayString
     *
     * @param  array $prepare_array
     * @param  string $prepare_prefix
     * @return object values - Array, params - String
     */
    public function makePrepareArrayString($prepare_array = array(), $prepare_prefix = "param")
    {
        $this->prepare_prefix = $prepare_prefix;

        $prepare_array = array_combine(array_map(function ($i) {
            return $this->prepare_prefix . '_' . $i;
        }, array_keys($prepare_array)), $prepare_array);
        $param_string = implode(',', array_keys($prepare_array));

        return (object) array(
            'values' => $prepare_array,
            'params' => $param_string,
        );
    }

    /**
     * lastInsertId
     *
     * @return integer|object  return lastInsertId
     */
    public function lastInsertId()
    {
        $this->db = $this->db_connect();
        try {
            $res = $this->db->lastInsertId();
            return $res;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function query($qry_string)
    {
        $this->db = $this->db_connect();
        try {
            $res = $this->db->query($qry_string);

            return $res;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function query_select_array($qry_string)
    {
        $this->db = $this->db_connect();
        try {
            $res = $this->db->query($qry_string);
            $result = $res->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function query_select_object($qry_string)
    {
        $this->db = $this->db_connect();

        try {
            $res = $this->db->query($qry_string);
            $result = $res->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function query_select_first_array($qry_string)
    {
        $this->db = $this->db_connect();
        try {
            $res = $this->db->query($qry_string);
            // $result = $res->fetch(PDO::FETCH_ASSOC);
            $result = $res->fetch(PDO::FETCH_ASSOC);
            return $result === false ? array() : $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function query_select_first_object($qry_string)
    {
        $this->db = $this->db_connect();
        try {
            $res = $this->db->query($qry_string);
            $result = $res->fetch(PDO::FETCH_OBJ);
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }
}
