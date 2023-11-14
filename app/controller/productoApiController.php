<?php
require_once('./app/model/productoModel.php');
require_once('./app/controller/apiController.php');
require_once('./app/helpers/verifyHelper.php');
require_once('./app/model/categoriaModel.php');
require_once('./app/helpers/authHelper.php');

class ProductoApiController extends ApiController{
    private $model;
    private $modelCategoria;
    private $authHelper;

    function __construct(){
        parent::__construct();
        $this->model = new ProductoModel();
        $this->modelCategoria = new CategoriaModel();
        $this->authHelper = new AuthHelper();
    }

    function getModel(){
        return $this->model;
    }

    function getModelCategoria(){
        return $this->modelCategoria;
    }

    public function getAuthHelper(){
        return $this->authHelper;
    }

    function getAll(){    

        $order = VerifyHelpers::queryOrder($_GET);
        $sort= VerifyHelpers::querySort($_GET,$this->getModel()->getColumns(MYSQL_TABLEPROD));

        $elem = VerifyHelpers::queryElem($_GET, $this->getModel()->getContElem(MYSQL_TABLEPROD));
        $limit = VerifyHelpers::queryLimit($_GET, $this->getModel()->getContElem(MYSQL_TABLEPROD));


        $filter = VerifyHelpers::queryFilter($_GET, $this->getModel()->getColumns(MYSQL_TABLEPROD));
        $value = VerifyHelpers::queryValue($_GET);
        $operator = VerifyHelpers::queryOperation($_GET);


        $arrayParams = array(   "order"     => ($order)      ? $_GET["order"]     : null,
                                "sort"      => ($sort)       ? $_GET["sort"]      : null,
                                "elem"      => ($elem)       ? $_GET["elem"]      : null,
                                "limit"     => ($limit)      ? $_GET["limit"]     : null,
                                "filter"    => ($filter)     ? $_GET["filter"]    : null,
                                "value"     => ($value)      ? $_GET["value"]     : null,
                                "operator"  => ($operator)   ? $_GET["operator"]  : null);


        $items = $this->getModel()->getProductoList($arrayParams);

        if(!empty($items)){
            $this->getView()->response($items,200);
        }else{
            $this->getView()->response(['msg' => 'No hay elementos para mostrar'],204);
        }
    }

    function getProducto($params = []){
        $producto = $this->getModel()->getProducto($params[':ID']);
        if (!empty($producto)) {
            $this->getView()->response($producto, 200);
        } else {
            $this->getView()->response(['msg' => 'El producto con el ID = ' . $params[':ID'] . ' No existe'], 404);
        }
    }

    function deleteProducto($params = []){       
        $id = $params[':ID'];
        $producto = $this->getModel()->getOnlyProducto($id);

        if (!empty($producto)) {
            $this->getModel()->deleteProducto($id);
            $this->getView()->response(['msg' => 'Se elimino con exito el ID = ' . $id], 200);
        } else {
            $this->getView()->response(['msg' => 'No se puedo eliminar el ID = ' . $id . ' No existe'], 404);
        }
    }

    function addProducto(){
        $user = $this->getAuthHelper()->currentUser();

        if(!$user){
            $this->getView()->response(['msg' => 'Usuario no autorizado'],401);
            return;
        }

        $body = $this->getData();

        $arrayValue = array ('nombre'         => $body->Nombre,
                             'descripcion'    => $body->Descripcion,
                             'talle'          => $body->Talle,
                             'precio'         => $body->Precio,
                             'idcategoria'    => $body->IDcategoria);

        if(!VerifyHelpers::verifyData($arrayValue)){
            $this->getView()->response(['msg' => 'Por favor completar todos los campos'], 404);
            return;
        }

        $categoria = $this->getModelCategoria()->getCategoria($arrayValue['idcategoria']);

        if(empty($categoria)){
            $this->getView()->response(['msg' => 'La categoria con el ID = '.$arrayValue['idcategoria'].' No existe'],404);
            return;
        }

        $id = $this->getModel()->addProducto($arrayValue);
        if(!empty($id)){
            $this->getView()->response(['msg' => 'El producto fue creado con exito con el ID = ' . $id], 201);
        }else {
            $this->getView()->response(['msg' => 'Falla en la actualizacion del ID: ' . $id], 500);
        }
    }
    
    function upDateProducto($params = []) {

        $user = $this->getAuthHelper()->currentUser();

        if(!$user){
            $this->getView()->response(['msg' => 'Usuario no autorizado'],401);
            return;
        }
        
        $id = $params[':ID'] ?? null;
    
        if (empty($id)) {
            $this->getView()->response(['msg' => 'ID  vacio'], 404);
            return;
        }
    
        $producto = $this->getModel()->getOnlyProducto($id);
    
        if (empty($producto)) {
            $this->getView()->response(['msg' => 'No se puedo actualizar el ID = ' . $id . ' No existe'], 404);
            return;
        }
    
        $body = $this->getData();
        $nombre = $body->Nombre ?? $producto->Nombre;
        $descripcion = $body->Descripcion ?? $producto->Descripcion;
        $talle = $body->Talle ?? $producto->Talle;
        $precio = $body->Precio ?? $producto->Precio;
        $idcategoria = $body->IDcategoria ?? $producto->IDcategoria;
        $idproducto = $body->IDproducto ?? $producto->IDproducto;

        $result = $this->getModel()->upDateProducto($nombre, $descripcion, $talle, $precio, $idcategoria, $idproducto);
    
        if ($result) {
            $this->getView()->response(['msg' => 'El producto con ID = ' . $id . ' fue actualizado con exito'], 200);
        } else {
            $this->getView()->response(['msg' => 'Falla en la actualizacion del ID: ' . $id], 500);
        }
    }
}