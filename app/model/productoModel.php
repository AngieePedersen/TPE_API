<?php
require_once "./app/Model/model.php";

class ProductoModel extends Model{

    public function getProductoList($arrayParams){
        $queryParams = $this->getQueryParams($arrayParams,MYSQL_TABLEPROD);

        $query = $this->getDB()->prepare("  SELECT IDproducto, productos.Nombre, Descripcion, Talle, Precio
                                            FROM `productos`
                                            INNER JOIN `categorias`
                                            ON productos.IDcategoria = categorias.IDcategoria "
                                            .$queryParams["filter"]
                                            .$queryParams["order"] 
                                            .$queryParams["pagination"]); 
        $query->execute();

        $productos = $query->fetchAll(PDO::FETCH_OBJ);

        return $productos;
    }

    public function getProducto($producto){
        $query = $this->getDB()->prepare("  SELECT IDproducto, productos.Nombre, Descripcion, Talle, Precio, productos.IDcategoria
                                            FROM `productos`
                                            INNER JOIN `categorias`
                                            ON productos.IDcategoria = categorias.IDcategoria 
                                            WHERE IDproducto = ? ");
        $query->execute([$producto]);

        $producto = $query->fetch(PDO::FETCH_OBJ);

        return $producto;
    }

    public function upDateProducto($nombre, $descripcion, $talle, $precio, $idcategoria, $idproducto){
        $query = $this->getDB()->prepare("UPDATE `productos` 
                                            SET Nombre = ?, Descripcion = ?, Talle = ?, Precio = ?, IDcategoria = ?,
                                            WHERE IDproducto = ?");
        $query->execute([$nombre, $descripcion, $talle, $precio, $idcategoria, $idproducto]);

        return $query;
    }

    public function deleteProducto($id){
        $query = $this->getDB()->prepare("DELETE FROM `productos` WHERE IDproducto = ?");
        $query->execute([$id]);

        return $query;
    }

    public function addProducto($arrayValue){
        $query = $this->getDB()->prepare("INSERT INTO `productos`(Nombre, Descripcion, Talle, Precio, IDcategoria) VALUES (?,?,?,?,?)");
        $query->execute([$arrayValue['nombre'], $arrayValue['descripcion'], $arrayValue['talle'], $arrayValue['precio'], $arrayValue['idcategoria']]);

        return $this->getDB()->lastInsertId();
    }

    public function getOnlyProducto($producto){
        $query = $this->getDB()->prepare("SELECT * FROM `productos` WHERE IDproducto = ?");
        $query->execute([$producto]);

        $producto = $query->fetch(PDO::FETCH_OBJ);

        return $producto;
    }
}