<?php

/**
 * Description of PCModelBuilder
 *
 * @author paolo
 */
final class PCModelManager {

    /**
     * 
     * @param PCMapper $mapper
     * @param array $values
     * @param array $onDuplicateUpdate
     * @return bool
     */
    public static function insertObject($mapper, $values, $onDuplicateUpdate = NULL) {

        $table_name = $mapper->getTableForInsertUpdate();
        /*
          if(isset($values['identifier'])){
          $identifier = $values['identifier'];
          PCCache::cacheProvider()->removeItem($mapper->getTableName(). "" . $identifier);
          }
         */
        $insertQuery = "INSERT INTO " . $table_name . "  ";




        $columns = "";
        $vals = "";
        $prepared_keys = array();
        $first = TRUE;
        foreach ($values as $key => $value) {
            $placeHolder = ":" . $key;



            if (!$first) {
                $columns .= ", " . $key;
                $vals .= ", " . $placeHolder;
            } else {
                $columns .= " " . $key;
                $vals .= " " . $placeHolder;
                $first = FALSE;
            }
            $prepared_keys[$placeHolder] = $value;
        }

        $insertQuery = $insertQuery . " ( " . $columns . " ) VALUES ( " . $vals . " ) ";

        $update = $onDuplicateUpdate != NULL;

        if ($update) {
            $up = "";
            $first = TRUE;
            foreach ($onDuplicateUpdate as $value) {
                if ($first) {
                    $up .= $value . " = :" . $value;
                    $first = FALSE;
                } else {
                    $up .= ", " . $value . " = :" . $value;
                }
            }
            $insertQuery .= " ON DUPLICATE KEY UPDATE  " . $up . "  ";
        }
        $insertQuery .= ";";

        $pdo = PCDatabase::getSharedDatabaseConnection();


        $prepared = $pdo->prepare($insertQuery);
        $result = $prepared->execute($prepared_keys);
        if ($result === FALSE) {
            error_log($prepared->queryString);
            c_dump($prepared->errorInfo());
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 
     * @param PCMapper $mapper
     * @param string $conditions
     * @param array $bindings key value array for the conditions
     * @param string $identifier the object identifier for removing the item from cache
     */
    public static function deleteObject($mapper, $conditions, $bindigngs = array(), $identifier = NULL) {
        $table_name = $mapper->getTableForInsertUpdate();


        if (isset($identifier)) {
            PCCache::cacheProvider()->removeItem($mapper->getCacheKey($keys));
        }

        $deleteQuery = "DELETE FROM " . $table_name . " WHERE " . $conditions . ";";

        $pdo = PCDatabase::getSharedDatabaseConnection();
        $prepared = $pdo->prepare($deleteQuery);

        if ($prepared === FALSE) {

            error_log($prepared->errorInfo());
            return FALSE;
        }

        $result = $prepared->execute($bindigngs);

        if ($result === FALSE) {
            c_dump($prepared->errorInfo());

            return FALSE;
        }

        return TRUE;
    }

    /**
     * 
     * @param PCMapper $mapper
     * @param array $keys
     * @param string $conditions
     * @param array $bindings
     * @return boolean
     */
    public static function updateObject($mapper, $keys, $conditions, $bindings = array()) {

        if (isset($conditions) == FALSE)
            throw new PCException("InternalInconsistency", 500);

        $table_name = $mapper->getTableForInsertUpdate();

        if (isset($keys['identifier'])) {
            PCCache::cacheProvider()->removeItem($mapper->getCacheKey($keys));
        }

        $update = "UPDATE $table_name SET ";

        $prepared_keys = array();
        $first = TRUE;
        foreach ($keys as $key => $value) {
            $placeHolder = ':' . $key;

            if ($first) {
                $first = FALSE;
                $update .= " $key =  $placeHolder ";
            } else {
                $update .= ", $key =  $placeHolder ";
            }

            $prepared_keys[$placeHolder] = $value;
        }

        $update .= " WHERE $conditions";

        $pdo = PCDatabase::getSharedDatabaseConnection();

        $prepared = $pdo->prepare($update);

        if ($prepared === FALSE) {

            c_dump($prepared->errorInfo());
            return FALSE;
        }

        $merged = array_merge($bindings, $prepared_keys);

        $result = $prepared->execute($merged);

        if ($result === FALSE) {
            c_dump($prepared->errorInfo());
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Returns an array of instanced subclass of PCModelObject according to $name
     * @param PCMapper $mapper a subclass of PCMapper
     * @param array $keys a key value array of elements for filtering search in database
     * @param array $optionalAttributes an array of strings containing the names of the optional fields to fetch
     * @param boolean $useCache if use the cache or not
     * @param string $limit the sql query limit
     * @param string $order the order specification
     * @return array an array of instanced subclass of PCModelObject according to $name
     */
    public static function fetchModelObjectInstances($mapper, $keys, $optionalAttributes = null, $useCache = false, $limit = null, $order = NULL) {
        /* if(empty($keys)){
          throw new Exception("Illegal argument, \"keys\" is empty");
          } */
        if ($mapper == null) {
            throw new Exception("Mapper must not be null");
        }


        $required_field = $mapper->getRequiredAttributes();


        if ($useCache && isset($keys['identifier'])) {
            $identifier = $keys['identifier'];
            $cache = PCCache::cacheProvider();
            //error_log("GETTING : " . $mapper->getCacheKey($keys));
            $item = $cache->getItem($mapper->getCacheKey($keys));
            if (isset($item) && $item !== FALSE) {
                //error_log("GETTED : " . $mapper->getCacheKey($keys));
                return $item;
            }
        }

        $fields = NULL;

        //unisco parametri opzionali (se presenti) a parametri richiesti
        if ($optionalAttributes == null) {
            $fields = $required_field;
        } else {
            $fields = array_merge($optionalAttributes, $required_field);
        }
        //creo stringa da parametri separati da virgola
        $fields_string = implode(",", $fields);

        $table_name = $mapper->getTableName();

        $select_stm = "SELECT " . $fields_string . " FROM " . $table_name . " WHERE ";

        $prepared_keys_array = array();

        $count = 0;
        $tot = count($keys);
        foreach ($keys as $key => $value) {
            $count++;

            $placeHolder = ":$key";
            $select_stm .= ($count == $tot) ? " $key = $placeHolder " : " $key = $placeHolder AND ";
            $prepared_keys_array[$placeHolder] = $value;
        }

        if ($tot == NULL) {
            $select_stm .= " TRUE";
        }

        if (isset($order)) {
            $select_stm .= ' ORDER BY ' . $order;
        }

        if (isset($limit)) {
            $select_stm .= ' LIMIT ' . $limit;
        }

        $pdo = PCDatabase::getSharedDatabaseConnection();

        $prepared = $pdo->prepare($select_stm);




        if ($prepared->execute($prepared_keys_array) == FALSE) {
            
            $message = "Errore database: (" . $prepared->errorCode() . ") " . $prepared->errorInfo()[1] . " " . $prepared->errorInfo()[2];
            throw new Exception($message);
        }

        $result = null;

        $toReturn = array();

        while (($result = $prepared->fetch(PDO::FETCH_ASSOC)) != NULL) {

            $toReturn[] = $mapper->getMappedInstance($result);
        }


        if ($useCache && count($toReturn) == 1) {
            $identifier = $keys['identifier'];
            //error_log("STORING: " . $mapper->getCacheKey($keys));
            PCCache::cacheProvider()->setItem($toReturn, $mapper->getCacheKey($keys), 300);
        }

        $prepared = NULL;

        return $toReturn;
    }

    /**
     * 
     * @param PCMapper $mapper a subclass of PCMapper
     * @param string $identifier
     * @param array $optionalAttributes
     * @return PCModelObject a PCModelObject subclass
     */
    public static function fetchObjectWithIdentifier($mapper, $identifier, $optionalAttributes = null, $useCache = false) {
        $result = static::fetchModelObjectInstances($mapper, array("identifier" => $identifier), $optionalAttributes, $useCache);
        if ($result == NULL || count($result) == 0)
            return NULL;
        return $result[0];
    }

}

