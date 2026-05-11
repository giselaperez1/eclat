<?php

class Producto {

    private $linkBD;              // almacena la conexión PDO que recibimos al instanciar la clase
    private $tabla = "productos"; // nombre de la tabla en MySQL - lo guardamos aquí para no repetirlo

    // cada propiedad representa una columna de la tabla productos en la bbdd
    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $categoria_id;  // clave foránea que apunta a la tabla categorias
    public $color;
    public $talla;
    public $imagen;        // guardamos solo el nombre del archivo 
                           // la ruta completa la montamos en la vista según dónde esté la imagen

    /*
     Constructor - recibe la conexión activa desde el archivo que instancia la clase
     Usamos inyección de dependencias para que la conexión venga de fuera
     y no tengamos que crearla dentro del modelo 
     */
    public function __construct($conexion){
        $this->linkBD = $conexion;
    }

    /*
     leerTodos() devuelve productos con filtros opcionales
    
     Los cuatro parámetros son opcionales - si no se pasan, devuelve todos los productos.
     Usamos LEFT JOIN con categorias para que salgan también los productos sin categoría asignada.
     @param $categoria  - id de la categoría para filtrar (null = todas)
     @param $orden      - 'barato' o 'caro' para ordenar por precio (vacío = más nuevos primero)
     @param $color      - nombre del color para filtrar (null = todos)
     @param $busqueda   - texto busca en nombre y descripción (null = sin búsqueda)
     */
    public function leerTodos($categoria = null, $orden = '', $color = null, $busqueda = null){

        // usamos WHERE 1=1 como base para poder añadir condiciones con and

        $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock,
                       p.color, p.talla, p.imagen, c.nombre as categoria_nombre
                FROM ".$this->tabla." p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE 1=1";

        // vamos añadiendo condiciones solo si el parámetro viene con valor
        if($categoria != null)
            $sql .= " AND p.categoria_id = :cat_id";

        if($color != null)
            $sql .= " AND p.color = :color";

        // LIKE nos permite buscar texto entro de un campo
        if($busqueda != null)
            $sql .= " AND (p.nombre LIKE :busqueda OR p.descripcion LIKE :busqueda)";

        // el orden lo decide el usuario con el desplegable del catálogo
        if($orden == 'barato')      $sql .= " ORDER BY p.precio ASC";   // ascendente = más barato primero
        elseif($orden == 'caro')    $sql .= " ORDER BY p.precio DESC";  // descendente = más caro primero
        else                        $sql .= " ORDER BY p.id DESC";      // por defecto mostramos los más recientes

        // prepare() previene inyección SQL
        // usamos :placeholders que PDO reemplaza de forma segura
        $consultaProd = $this->linkBD->prepare($sql);

        // bindParam() asocia cada placeholder con su valor real justo antes de ejecutar
        if($categoria != null)  $consultaProd->bindParam(':cat_id', $categoria);
        if($color != null)      $consultaProd->bindParam(':color', $color);

        if($busqueda != null){
            // añadimos % al principio y al final para buscar el texto en cualquier posición
            $termino = '%'.$busqueda.'%';
            $consultaProd->bindParam(':busqueda', $termino);
        }

        $consultaProd->execute();
        return $consultaProd; // devolvemos el statement para recorrerlo con while() en la vista
    }

    /*
     * obtenerColoresUnicos() devuelve la lista de colores para el filtro del catálogo
     
      Usamos DISTINCT para que cada color aparezca una sola vez aunque haya
      muchos productos del mismo color. El ORDER BY lo ordenamos alfabéticamente.
     */
    public function obtenerColoresUnicos(){
        $sql = "SELECT DISTINCT color FROM ".$this->tabla."
                WHERE color IS NOT NULL AND color != ''
                ORDER BY color ASC";
        $q = $this->linkBD->prepare($sql);
        $q->execute();
        return $q;
    }
}
?>
