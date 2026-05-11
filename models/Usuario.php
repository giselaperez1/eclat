<?php
/*
 Gestiona todo lo relacionado con las cuentas de clientes:
registro de nuevos usuarios y verificación de credenciales en el login.
 */
class Usuario {

    private $linkBD;             // conexión PDO 
    private $tabla = "usuarios"; // nombre de la tabla en la bbdd

    // propiedades de columnas de la tabla usuarios
    public $id;
    public $nombre;
    public $apellidos;
    public $email;
    public $contrasena;  // aquí se guarda el hash bcrypt
    public $rol;         // dos valores posibles: 'cliente' o 'admin'

    function __construct($conexion){
        $this->linkBD = $conexion;
    }

    /*
     * registrar() inserta un nuevo usuario en la base de datos
      Antes de insertar limpiamos los datos con htmlspecialchars() y strip_tags()
     La contraseña se hashea con BCRYPT antes de guardarla.
     */
    public function registrar(){

        $sql = "INSERT INTO ".$this->tabla."
                    (nombre, apellidos, email, contrasena, rol)
                VALUES
                    (:nombre, :apellidos, :email, :contrasena, :rol)";

        $consultaReg = $this->linkBD->prepare($sql);

        // saneamos los datos de texto para evitar inyección de código 
        // strip_tags() elimina etiquetas HTML y htmlspecialchars() convierte < > " en entidades seguras
        $this->nombre    = htmlspecialchars(strip_tags($this->nombre));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->email     = htmlspecialchars(strip_tags($this->email));

        // PASSWORD_BCRYPT genera un hash de 60 caracteres con salt aleatorio incluido
        // cada vez que se hashea la misma contraseña el resultado es diferente 
        $hashClave = password_hash($this->contrasena, PASSWORD_BCRYPT);

        $consultaReg->bindParam(":nombre",     $this->nombre);
        $consultaReg->bindParam(":apellidos",  $this->apellidos);
        $consultaReg->bindParam(":email",      $this->email);
        $consultaReg->bindParam(":contrasena", $hashClave);  // guardamos el hash
        $consultaReg->bindParam(":rol",        $this->rol);

        if($consultaReg->execute()) return true;
        return false;
    }

    /*
     * emailExiste()  busca un email en la bbdd y carga los datos del usuario
     */
    public function emailExiste(){

        $sql = "SELECT id, nombre, apellidos, contrasena, rol
                FROM ".$this->tabla."
                WHERE email = :email
                LIMIT 1";  // LIMIT 1 porque el email es único, solo puede haber uno

        $consultaEmail = $this->linkBD->prepare($sql);
        $this->email   = htmlspecialchars(strip_tags($this->email));
        $consultaEmail->bindParam(":email", $this->email);
        $consultaEmail->execute();

        $num = $consultaEmail->rowCount(); // rowCount() nos dice cuántas filas devolvió la consulta

        if($num > 0){
            // fetch() con FETCH_ASSOC devuelve la fila como array asociativo (clave => valor)
            // cargamos cada campo en la propiedad correspondiente del objeto
            $datos = $consultaEmail->fetch(PDO::FETCH_ASSOC);
            $this->id         = $datos['id'];
            $this->nombre     = $datos['nombre'];
            $this->apellidos  = $datos['apellidos'];
            $this->contrasena = $datos['contrasena']; // hash bcrypt para usar con password_verify()
            $this->rol        = $datos['rol'];
            return true;
        }
        return false;
    }
}
?>
