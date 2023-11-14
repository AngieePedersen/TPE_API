# TPE_API
Los archivos se subieron en la rama "master".

La descripcion de la API-REST se encuentra debajo.

URL: http://localhost/WEB2_TPE_API/api/productos

## GET Obtener producto

### Request

`GET /productos`

    Obtiene una lista con todos los productos disponibles.
    Por defecto la lista se encuentra ordenada por ID en forma descendente.

# Orden
`GET /productos?sort=Nombre&order=desc`

    Obtiene una lista con todos los productos disponibles ordenados por un campo y un orden determinado. 
    De no existir el campo y/o el orden devuelve la lista ordenada por defecto.

# Paginacion

`GET /productos?elem=2&limit=2`

    Obtiene una lista de tamaño limitado por los parametros de paginacion. 
    elem: determina el producto desde el que inicia. 
    limit: la cantidad de elementos a mostrar.

# Filtro
`GET /productos?filter=Precio&value=25000`

    Obtiene una lista filtrada por el campo deseado. 
    Ejemplo: Precio = $25000.   

`GET /productos?filter=Precio&value=9000&operator=>=`
    Ejemplo: Todos los precios mayores o iguales a 9000-.

# Aclaracion:
    Si los parametros son erroneos devuelve la lista completa.

### Response

#### 200

    HTTP/1.1 200 OK
    Status: 200 OK
    Content-Type: application/json
    [{
        "IDproducto": 2,
        "Nombre": "Remera London",
        "Descripcion": "Remera de algodon basica blanca",
        "Talle": "1",
        "Precio": 8000,
        "IDcategoria": 1,
    },{
        ...
        }]

#### 204

    HTTP/1.1 204 No content
    Status: 204 No content
    "No hay elementos para mostrar"


## GET Obtiene producto por id

### Request

`GET /productos/id`

    Obtiene un producto especifico filtrado por id.

### Response

#### 200 OK

    HTTP/1.1 200 OK
    Status: 200 OK
    Content-Type: application/json

    {
        "IDproducto": 2,
        "Nombre": "Remera London",
        "Descripcion": "Remera de algodon basica blanca",
        "Talle": "1",
        "Precio": 8000,
        "IDcategoria": 1,
    }

#### 404 Not found

    HTTP/1.1 404 Not found
    Status: 404 Not found
    "El producto con el ID = 'id' No existe"



## POST Crea nuevo producto


### Request

`POST /productos/Bearer TOKEN`


    Crea un nuevo producto.
    Se envia el siguiente JSON:

    {
    "Nombre": String,
    "Descripcion": String,
		"Talle": Int,
    "Precio": Int,
    "IDcategoria": Int
	}

# Aclaracion:
    El campo IDcategoria tiene que coincidir con IDcategoria de la categoria correspondiente

### Response

#### 201 Created

    HTTP/1.1 201 Created
    Status: 201 Created
    Content-Type: application/json
    
    "El producto fue creado con exito con el ID = 'id' "

#### 400 Bad Request

    HTTP/1.1 400 Bad Request
    Status: Bad Request

    1 - 'Por favor completar todos los campos'
    2 - 'La categoria con con el ID = 'IDcategoria' No existe'

#### 401 Unauthorized

    HTTP/1.1 401 Unauthorized
    Status: 401 Unauthorized
    "Usuario no autorizado"

## PUT Modifica un producto

    Modifica un producto especifico mediante id.

### Request

`PUT /productos/id/Bearer TOKEN`

 Se envia el siguiente JSON:

    {
    "Nombre": String,
    "Descripcion": String,
		"Talle": Int,
    "Precio": Int,
    "IDcategoria": Int
	}

# Aclaracion:
        Si algun campo no es cargado, no se actualiza. Mantine el valor que tenia.
        El campo IDcategoria tiene que coincidir con IDcategoria de la categoria correspondiente.

### Response

#### 200 OK

    HTTP/1.1 200 OK
    Status: 200 OK
    Content-Type: application/json

    "El producto con ID = ' id ' fue actualizado con exito"

#### 400 Bad Request

    HTTP/1.1 400 Bad Request
    Status: Bad Request

    "No se puedo actualizar el ID = 'id' No existe"

#### 401 Unauthorized

    HTTP/1.1 401 Unauthorized
    Status: 401 Unauthorized
    "Usuario no autorizado"


## DELETE Elimina un producto

### Request

`DELETE /productos/id`

    Elimina el producto indicado mediante el id.

### Response

#### 200 OK

    HTTP/1.1 200 OK
    Status: 200 OK

    "Se elimino con exito el ID = 'id' "

#### 404 Not found

    HTTP/1.1 404 Not found
    Status: 404 Not found
    "No se puedo eliminar el ID = ' id  ' No existe"


## GET TOKEN

### Request

`GET /productos/user/token`

    Basic Auth.

    user:"...";
    password: "...";

    Se solicita token para poder realizar request de PUT y POST

### Response

#### 200 OK

    HTTP/1.1 200 OK
    Status: 200 OK

    "Devuelve TOKEN generado"

#### 401 Unauthorized

    HTTP/1.1 401 Unauthorized
    Status: 401 Unauthorized
    "Los encabezados de Autenticacion son incorrectos"






## GET Obtener Categoria

### Request

`GET /categorias`

    Obtiene una lista con todos las categorias disponibles.

# Orden
`GET /categorias?sort=Nombre&order=desc`

    Obtiene una lista con todos las categorias disponibles ordenados por un campo y orden determinado.

# Paginacion
`GET /Categorias?elem=2&limit=1`

    Obtiene una lista de tamaño limitado por los parametros de paginacion. 
    elem: determina la categoria desde el que inicia 
    limit: la cantidad a mostrar.

# Filtro
`GET /categorias?filter=Nombre&value=Accessories`

    Obtiene una lista filtrada por el campo deseado. 
    Ejemplo: Nombre = Accessories

    Si se quiere ingresar un operador se debe hacer de la siguiente manera:

`GET /categorias?filter=Nombre&value=Acc%&operator=LIKE`
    Ejemplo: Todas las categorias que empiecen con "Acc". 

# Aclaracion:
    Si los parametros son erroneos devuelve la lista completa.

### Response

#### 200

    HTTP/1.1 200 OK
    Status: 200 OK
    Content-Type: application/json
    [{
		"IDcategoria": 3,
		"Nombre": "Accesories",
	    },{
        ...
        }]

#### 204

    HTTP/1.1 204 No content
    Status: 204 No content
    "La categoria con el ID = 'id' No existe"


## GET Obtiene Categoria por id

### Request

`GET /catgeorias/id`

    Obtiene una categoria especifica filtrado por id.

### Response

#### 200 OK

    HTTP/1.1 200 OK
    Status: 200 OK
    Content-Type: application/json

{
	"IDcategoria": 3,
	"Nombre": "Accessories",
}

#### 404 Not found

    HTTP/1.1 404 Not found
    Status: 404 Not found
    "La categoria con el ID = "id". No existe"



## POST Crea nueva Categoria


### Request

`POST /categorias/Bearer TOKEN`


    Crea un nueva categoria.

    {
        "IDcategoria": Autoincremental,
        "Nombre": String
    
    }

### Response

#### 201 Created

    HTTP/1.1 201 Created
    Status: 201 Created
    Content-Type: application/json

    "La categoria fue creada con exito con el ID = 'id'"

#### 400 Bad Request

    HTTP/1.1 400 Bad Request
    Status: Bad Request

    "No hay elementos para agregar"

#### 401 Unauthorized

    HTTP/1.1 401 Unauthorized
    Status: 401 Unauthorized
    "Usuario no autorizado"


## PUT Modifica una categoria

    Modifica una categoria especifico mediante id.

### Request

`PUT /categorias/id/Bearer TOKEN`

    {
        "Nombre": String
    }

### Response

#### 200 OK

    HTTP/1.1 200 OK
    Status: 200 OK
    Content-Type: application/json

    "La categoria ID = 'id' fue actualizada con exito"

#### 401 Unauthorized

    HTTP/1.1 401 Unauthorized
    Status: 401 Unauthorized
    "Usuario no autorizado"


#### 400 Bad Request

    HTTP/1.1 400 Bad Request
    Status: Bad Request

    1 - "Ingrese los campos correctamente"
    2 - "No se puedo actualizar el ID = 'id' No existe"


## DELETE Elimina un producto

### Request

`DELETE /categorias/id`

    Elimina la categoria indicada mediante el id.

### Response

#### 200 OK

    HTTP/1.1 200 OK
    Status: 200 OK

    "Se elimino con exito el ID = "id" "

#### 404 Not found

    HTTP/1.1 404 Not found
    Status: 404 Not found
    "No se puedo eliminar el ID = "id" No existe "

#### 400 Bad Request

    HTTP/1.1 400 Bad Request
    Status: Bad Request

    "Ingrese los campos correctamente"



## GET TOKEN

### Request

`GET /categorias/user/token`

    Basic Auth.

    user:" ";
    password: " ";

    Se solicita token para poder realizar request de PUT y POST

### Response

#### 200 OK

    HTTP/1.1 200 OK
    Status: 200 OK

    "Devuelve TOKEN generado"

#### 401 Unauthorized

    HTTP/1.1 401 Unauthorized
    Status: 401 Unauthorized
    "Los encabezados de Autenticacion son incorrectos"
