<?php

/**
 * Autoload de clases
 * @param string Nombre de la clase instanciada
 */
function autoloading($className){
    // Path a la clase instanciada
    $file = "clases/".$className.".php";
    // Comprobamos si la ruta al fichero es válida
    if(file_exists($file)){
        require_once $file;
    }else{
        die("La clase $className no se encuentra.");
    }
}

// Ejecutamos el autoload
spl_autoload_register("autoloading");