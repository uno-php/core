<?php

namespace Uno\Database;

class DB extends DBInteractor
{
    /****************************************
     *   SELECT QUERIES
     *****************************************/
    public function getAllData($tableName)
    {
        $sql = "SELECT * from {$tableName};";

        return $this->executeQuery($sql);
    }

    public function getTotalData($tableName)
    {
        $sql = "SELECT COUNT(*) as total FROM {$tableName};";

        return $this->executeQuery($sql)[0]['total'];
    }

    public function getTotalDataWhere($tableName, $condition)
    {
        $sql = "SELECT COUNT(*) as total FROM {$tableName} 
                WHERE {$condition};";

        return $this->executeQuery($sql)[0]['total'];
    }

    public function getAllDataWhere($tableName, $condition)
    {
        $sql = "SELECT * FROM {$tableName} 
                WHERE {$condition}";

        return $this->executeQuery($sql);
    }

    public function getAllDataWhereOrder($tableName, $where, $order)
    {
        $sql = "SELECT * FROM {$tableName} 
                WHERE {$where} ORDER BY {$order};";

        return $this->executeQuery($sql);
    }

    public function getDataTotalJoin($tableName, $otherTableName, $condition, $key)
    {
        $sql = "SELECT COUNT(*) as total FROM {$tableName}
		 		INNER JOIN {$otherTableName} ON
		 		{$condition} WHERE {$key};";

        return $this->executeQuery($sql)[0]['total'];
    }

    public function getPaginatedArticles($tableName, $order, $start, $perPage)
    {
        $sql = "SELECT * FROM {$tableName} 
                ORDER BY {$order} 
                LIMIT {$start}, {$perPage};";

        return $this->executeQuery($sql);
    }

    public function getPaginatedArticlesJoin($tableName, $otherTable, $condition, $where, $order, $start, $perPage)
    {
        $sql = "SELECT * FROM {$tableName} 
                JOIN {$otherTable} ON {$condition} 
                WHERE {$where} ORDER BY {$order} 
                LIMIT {$start}, {$perPage};";

        return $this->executeQuery($sql);
    }

    public function getAllDataInnerJoin($tableName, $otherTable, $condition)
    {
        $sql = "SELECT * FROM {$tableName} 
                JOIN {$otherTable} ON {$condition};";

        return $this->executeQuery($sql);
    }

    public function getAllDataInnerJoinWhere($tableName, $otherTable, $condition, $where)
    {
        $sql = "SELECT * FROM {$tableName} 
                JOIN {$otherTable} ON {$condition} 
                WHERE {$where};";

        return $this->executeQuery($sql);
    }

    public function getAllDataInnerJoinOrder($tableName, $otherTable, $condition, $order)
    {
        $sql = "SELECT * FROM {$tableName} 
                JOIN {$otherTable} ON {$condition} 
                ORDER BY {$order};";

        return $this->executeQuery($sql);
    }

    public function getAllDataInnerJoinWhereOrder($tableName, $otherTable, $condition, $where, $order)
    {
        $sql = "SELECT * FROM {$tableName} 
                JOIN {$otherTable} ON {$condition} 
                WHERE {$where} ORDER BY {$order};";

        return $this->executeQuery($sql);
    }

    public function getAllDataInnerJoinGroupBy($tableName, $otherTable, $condition, array $fields = ['*'], $groupBy)
    {
        $fields = convertToCommaSeparatedString($fields);

        $sql = "SELECT {$fields} FROM {$tableName} 
                JOIN {$otherTable} ON {$condition} 
                GROUP BY {$groupBy};";

        return $this->executeQuery($sql);
    }

    public function getAllDataInnerJoinWhereGroupBy($tableName, $otherTable, $condition, array $fields = ['*'], $where, $groupBy)
    {
        $fields = convertToCommaSeparatedString($fields);

        $sql = "SELECT {$fields} FROM {$tableName} 
                JOIN {$otherTable} ON {$condition} 
                WHERE {$where} GROUP BY {$groupBy};";

        return $this->executeQuery($sql);
    }

    /****************************************
    *   UPDATE, INSERT AND DELETE
    *****************************************/
    public function addData($tableName, $data)
    {
        $fieldNames = array_keys($data);

        $fields = convertToCommaSeparatedString($fieldNames);

        $boundNames = array_map(function($name){
            return ":" . $name;
        }, $fieldNames);

        $fieldsValue = convertToCommaSeparatedString($boundNames);

        $sql = "INSERT INTO {$tableName} ({$fields}) value ({$fieldsValue});";

        return $this->executeAction($sql, $data);
    }

    public function updateData($tableName, $condition, $where)
    {
        $sql = "UPDATE {$tableName} SET {$condition} WHERE {$where};";

        return $this->executeAction($sql);
    }

    public function deleteData($tableName, $where)
    {
        $sql = "DELETE FROM {$tableName} WHERE {$where};";

        return $this->executeAction($sql);
    }

}