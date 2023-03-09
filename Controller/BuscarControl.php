<!-- <?php include_once "../config.php" ?> -->

<?php

class BuscarControl
{
    public function buscar()
    {
        $arr = [];
        $data = data_submitted();

        if (isset($data["busqueda"])) {
            $nombre["busqueda"] = $data["busqueda"];
        } else $nombre = "";
        // mostrarArray($data);

        $objAbmProducto = new AbmProducto();
        $arr = $objAbmProducto->buscar($nombre);
        return $arr;
    }
}
