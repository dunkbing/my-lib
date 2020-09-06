<?php
namespace Lib\Core;
use PDO;
use ReflectionClass;
use ReflectionProperty;
use PDOException;
abstract class Entity {
  protected PDO $db;
  protected $tableName;

  public function __construct() {
    try {
      $this->db = new PDO('mysql:host=localhost;dbname=product_api', 'root', 'dunkbing4869');
    } catch (PDOException $e) {
      throw new \Exception('Error creating a database connection ');
    }
  }

  public function save() {
    $class = new ReflectionClass($this);
    $tableName = '';
    if ($this->tableName != '') {
      $tableName = $this->tableName;
    } else {
      $tableName = strtolower($class->getShortName());
    }
    $propToImplode = [];
    
    foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
      $propertyName = $property->getName();
      if ($this->{$propertyName} != null) {
        $propToImplode[] = '`'.$propertyName.'` = "'.$this->{$propertyName}.'"';
      }
    }

    $setClause = implode(',', $propToImplode);
    $sqlQuery = '';

    if ($this->id > 0) {
      $sqlQuery = 'UPDATE `'.$tableName.'` SET '.$setClause.' WHERE id = '.$this->id;
    } else {
      $sqlQuery = 'INSERT INTO `'.$tableName.'` SET '.$setClause;
    }
    echo $sqlQuery;
    $result = $this->db->exec($sqlQuery);

    return $result;
  }

  public function find ($query = '', $options = [], $limit=null) {

    $result = [];

    $whereClause = '';
    $whereConditions = [];

    if (!empty($options)) {
      foreach ($options as $key => $value) {
        $whereConditions[] = '`'.$key.'` = "'.$value.'"';
      }
      $whereClause = " WHERE ".implode(' AND ',$whereConditions);
    }

    $query .= $whereClause;
    if ($limit) {
      $query .= " LIMIT ".$limit;
    }
    $stmt = $this->db->prepare($query);
   /*  $raw = $this->db->query($query);
    echo $query;

    foreach ($raw as $rawRow) {
      $result[] = self::morph($rawRow);
    }

    return $result; */
    // echo $query;
    $stmt->execute();
    return $stmt;
  }

  public static function morph(array $object) {
    $class = new ReflectionClass(get_called_class());
    $entity = $class->newInstance();
    foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $prop) {
      if (isset($object[$prop->getName()])) {
        $prop->setValue($entity, $object[$prop->getName()]);
      }
    }
    return $entity;
  }
}