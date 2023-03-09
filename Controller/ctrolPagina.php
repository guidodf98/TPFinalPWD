<?php
//include_once('../config.php');

class ctrolPagina
{

    function ctrl_acceso()
    {

        $resp = false;
        $data = data_submitted();

        if (isset($data['idmenu'])) {
            $abmMenuRol = new AbmMenuRol;
            $col = $abmMenuRol->buscar(['idmenu' => $data['idmenu'], 'idrol' => $_SESSION['rol']]);

            if (count($col) != 0) {
                $resp = true;
            }
        }
        return $resp;
    }
}
