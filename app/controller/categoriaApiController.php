<?php
    require_once ('./app/model/categoriaModel.php');
    require_once ('./app/controller/apiController.php');
    require_once ('./app/helpers/verifyHelper.php');
    require_once ('./app/helpers/authHelper.php');

    class CategoriaApiController extends ApiController{
        private $model;
        private $authHelper;

        function __construct(){
            parent::__construct();
            $this->model = new CategoriaModel();
            $this->authHelper = new AuthHelper();
        }

        function getModel(){
            return $this->model;
        }

        public function getAuthHelper(){
            return $this->authHelper;
        }

        function getAll(){

            $order = VerifyHelpers::queryOrder($_GET);
            $sort= VerifyHelpers::querySort($_GET,$this->getModel()->getColumns(MYSQL_TABLECAT));

            $elem = VerifyHelpers::queryElem($_GET, $this->getModel()->getContElem(MYSQL_TABLECAT));
            $limit = VerifyHelpers::queryLimit($_GET, $this->getModel()->getContElem(MYSQL_TABLECAT));

            $filter = VerifyHelpers::queryFilter($_GET, $this->getModel()->getColumns(MYSQL_TABLECAT));
            $value = VerifyHelpers::queryValue($_GET);
            $operator = VerifyHelpers::queryOperation($_GET);

            $arrayParams = array(   "order"     => ($order)     ? $_GET["order"]     : null,
                                    "sort"      => ($sort)      ? $_GET["sort"]      : null,
                                    "elem"      => ($elem)      ? $_GET["elem"]      : null,
                                    "limit"     => ($limit)     ? $_GET["limit"]     : null,
                                    "filter"    => ($filter)    ? $_GET["filter"]    : null,
                                    "value"     => ($value)     ? $_GET["value"]     : null,
                                    "operator"  => ($operator)  ? $_GET["operator"]  : null);
            
            $items = $this->getModel()->getAllCategoria($arrayParams);

            if(!empty($items)){
                $this->getView()->response($items,200);
            }else{
                $this->getView()->response(['msg' => 'No hay elementos para mostrar'],204);
            }
        }

        function getCategoria($params = []){
            $categoria = $this->getModel()->getCategoria($params[':ID']);
            if(!empty($categoria)){
                $this->getView()->response($categoria,200);
            }else{
                $this->getView()->response(['msg' => 'La categoria con el ID = '.$params[':ID'].' No existe'],404);
            }
        }

        public function deleteCategoria($params = []){
            if(!empty($params) && is_numeric($params[':ID'])){
                $id=$params[':ID'];
                try{
                    $deleteCat =$this->getModel()->deleteCategoria($id);
                    if($deleteCat){
                        $this->getView()->response(['msg' => 'Se elimino con exito el ID = '.$id], 200);
                    }else{
                        $this->getView()->response(['msg' => 'No se puedo eliminar el ID = '.$id.' No existe'], 404);
                    }
                }catch(PDOException $exc){
                    $this->getView()->response(['msg' => 'No se puedo elimiar la categoria. Verificar productos asociados '.$exc],400);
                }
            }else{
                $this->getView()->response(['msg' => 'Ingrese los campos correctamente'], 400);
            }
        }

        function addCategoria(){
            $user = $this->getAuthHelper()->currentUser();

            if(!$user){
                $this->getView()->response(['msg' => 'Usuario no autorizado'],401);
                return;
            }
            $body = $this->getData();
            
            if(!VerifyHelpers::verifyData($body)){
                $this->getView()->response(['msg' => 'No hay elementos para agregar'], 400);
                return;
            }

            $nombre = $body->nombre;
    
            $id = $this->getModel()->addCategoria($nombre);
            if(!empty($id)){
                $this->getView()->response(['msg' => 'La categoria fue creada con exito con el ID = ' . $id], 201);
            }else {
                $this->getView()->response(['msg' => 'Falla en la actualizacion del ID: ' . $id], 500);
            }
        }
        
        function upDateCategoria($params = []) {
            $user = $this->getAuthHelper()->currentUser();
    
            if(!$user){
                $this->getView()->response(['msg' => 'Usuario no autorizado'],401);
                return;
            }
            $id = $params[':ID'] ?? null;
        
            if (empty($id)) {
                $this->getView()->response(['msg' => 'Ingrese los campos correctamente'], 400);
                return;
            }
        
            $categoria = $this->getModel()->getCategoria($id);
        
            if (empty($categoria)) {
                $this->getView()->response(['msg' => 'No se puedo actualizar el ID = ' . $id . ' No existe'], 400);
                return;
            }
        
            $body = $this->getData();
            $nombre = $body->nombre ?? $categoria->Nombre;
            $idcategoria = $body->idcategoria ?? $categoria->IDcategoria;
            
            $result = $this->getModel()->upDateCategoria($nombre, $idcategoria);
        
            if ($result) {
                $this->getView()->response(['msg' => 'La categoria ID = ' . $id . ' fue actualizada con exito'], 200);
            } else {
                $this->getView()->response(['msg' => 'Falla en la actualizacion del ID: ' . $id], 500);
            }
        }
    
    } 
