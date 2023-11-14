<?php
    require_once ('config.php');
    require_once ('libs/router.php');
    require_once ('./app/controller/productoApiController.php');
    require_once ('./app/controller/categoriaApiController.php');
    require_once ('./app/controller/userApiController.php');


    $router = new Router();

    #producto          endpoint       verbo        controller           método

    $router->addRoute('productos',     'GET',    'ProductoApiController', 'getAll');
    $router->addRoute('productos',     'POST',   'ProductoApiController', 'addProducto');
    $router->addRoute('productos/:ID', 'GET',    'ProductoApiController', 'getProducto');
    $router->addRoute('productos/:ID', 'PUT',    'ProductoApiController', 'upDateProducto');
    $router->addRoute('productos/:ID', 'DELETE', 'ProductoApiController', 'deleteProducto');
    

    #categoria         endpoint        verbo     controller             método

    $router->addRoute('categorias',     'GET',    'CategoriaApiController', 'getAll');
    $router->addRoute('categorias',     'POST',   'CategoriaApiController', 'addCategoria');
    $router->addRoute('categorias/:ID', 'GET',    'CategoriaApiController', 'getCategoria');
    $router->addRoute('categorias/:ID', 'PUT',    'CategoriaApiController', 'upDateCategoria');
    $router->addRoute('categorias/:ID', 'DELETE', 'CategoriaApiController', 'deleteCategoria');

    $router->addRoute('user/token',  'GET',    'UserApiController',   'getToken');

    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);