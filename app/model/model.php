<?php
require_once "./config.php";

class Model{
  private $db;

  public function __construct(){
    $this->db = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB . ';charset=utf8', MYSQL_USER, MYSQL_PASS);
  }

  public function getDB(){
    return $this->db;
  }

  public function getColumns($table){
    $query = $this->getDB()->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?");
    $query->execute([$table]);

    $colums = $query->fetchAll(PDO::FETCH_COLUMN);

    return $colums;
  }

  public function getContElem($table){
    $query = $this->getDB()->prepare("SELECT count(*) FROM $table");
    $query->execute();

    $contElement = $query->fetch(PDO::FETCH_NUM);

    return $contElement;
  }

  public function getQueryParams($arrayParams, $table){
    $querryParams = array(
      "filter"     => "",
      "order"      => "",
      "pagination" => ""
    );
    
    //control orden
    $querryParams["order"] = $this->orderBy($arrayParams, $table);

    //control paginado
    $querryParams["pagination"] = $this->page($arrayParams);

    //control filtro
    $querryParams["filter"] = $this->filter($arrayParams);

    return $querryParams;
  }

  private function orderBy($arrayParams,$table){
    if ($arrayParams["sort"] != null) {
      if ($arrayParams["order"] != null) {
        return " ORDER BY " . $arrayParams["sort"] . " " . $arrayParams["order"];
      } else {
        return  " ORDER BY " . $arrayParams["sort"] . " ASC ";
      }
    }
    return ($table == MYSQL_TABLEPROD) ? " ORDER BY productos.IDcategoria ASC " : " ORDER BY IDcategoria ASC ";
  }

  private function page($arrayParams){
    if (($arrayParams["elem"] != null) && ($arrayParams["limit"] != null)) {
      return " LIMIT " . $arrayParams["elem"] . ", " . $arrayParams["limit"];
    }
  }

  private function filter($arrayParams){
    
    if (($arrayParams["filter"] != null) && ($arrayParams["value"] != null)) {
      
      if ($arrayParams["filter"] == "categorias"){
        $arrayParams["filter"] = ".".$arrayParams["filter"];
      }
        switch($arrayParams["operator"]){
          case '<':
          case '>':
          case '=':
          case '<=':
          case '>=':
          case '<>':
          case 'LIKE': return " WHERE " . $arrayParams["filter"] . "  " . $arrayParams["operator"] . " '" .$arrayParams["value"]. "'";
          break;
          case "NULL" : return " WHERE " . $arrayParams["filter"] . " = '" . $arrayParams["value"] ."' ";
          break;

          default : return " WHERE " . $arrayParams["filter"] . " = '" . $arrayParams["value"] ."' ";
          break;
        }
    }

  }

}