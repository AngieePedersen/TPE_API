<?php

class CategoriaModel extends Model{

    public function getAllCategoria($arrayParams){

        $queryParams = $this->getQueryParams($arrayParams, MYSQL_TABLECAT);

        $query = $this->getDB()->prepare("SELECT * FROM `categorias` "
                                                                    .$queryParams["filter"]
                                                                    .$queryParams["order"] 
                                                                    .$queryParams["pagination"]);
        $query->execute();

        $productoCat = $query->fetchAll(PDO::FETCH_OBJ);

        return $productoCat;
    }

    public function getCategoria($IDcategoria){
        $query = $this->getDB()->prepare("SELECT * FROM `categorias` WHERE IDcategoria = ?");
        $query->execute([$IDcategoria]);

        $productoCat = $query->fetch(PDO::FETCH_OBJ);

        return $productoCat;
    }

    public function upDateCategoria($nombre, $idcategoria){
        $query = $this->getDB()->prepare("UPDATE `categorias` SET Nombre = ? WHERE IDcategoria = ?");
        $query->execute([$nombre, $idcategoria]);

        return $query;
    }

    public function deleteCategoria($categoria){
        $query = $this->getDB()->prepare("DELETE FROM `categorias` WHERE IDcategoria = ?");
        $query->execute([$categoria]);

        return $query;
    }

    public function addCategoria($nombre){
        $query = $this->getDB()->prepare("INSERT INTO `categorias`(Nombre) VALUES (?)");
        $query->execute([$nombre]);

        return $this->getDB()->lastInsertId();
    }
}