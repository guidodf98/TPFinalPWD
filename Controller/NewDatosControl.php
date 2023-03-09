<?php

class NewDatosControl
{
    public function newMenu($data)
    {
        $resp = false;
        if (isset($data['menombre'])) {
            $abmMenu = new AbmMenu;
            $resp = $abmMenu->alta($data);
        } else {
            $resp = false;
            $retorno['errorMsg'] = "Error al crear nuevo menu";
        }
        $retorno['respuesta'] = $resp;
        return $retorno;
    }

    public function newMenuRol($data)
    {
        $resp = false;

        if (isset($data['idmenu']) && isset($data['idrol'])) {
            $abm = new AbmMenuRol();
            $col = $abm->buscar(['idmenu' => $data['idmenu'], "idrol" => $data['idrol']]);
            if (count($col) == 0) {

                $resp = $abm->alta($data);
            }
        }

        $retorno['respuesta'] = $resp;
        $retorno['errorMsg'] = "Error: El rol ya tiene permiso a ese menu.";
        return $retorno;
    }

    public function newRol($data)
    {
        $resp = false;
        if (isset($data['idrol']) && isset($data['rodescripcion'])) {
            $abmRol = new AbmRol;
            if ($resp = $abmRol->alta($data)) {
                $resp = true;
            } else {
                $retorno['errorMsg'] = "error un nuevo rol";
            }
        }
        if (!$resp) {
        }
        $retorno['respuesta'] = $resp;
        return $retorno;
    }

    public function newUsuario($data)
    {
        $resp = false;
        if (isset($data['usnombre']) && isset($data['uspass'])) {
            $data['uspass'] = md5($data['uspass']);
            $abmUsuario = new AbmUsuario();
            $col = $abmUsuario->buscar(['usnombre' => $data['usnombre']]);
            if (count($col) == 0) {
                $resp = $abmUsuario->alta($data);
            }
        }
        $retorno['respuesta'] = $resp;
        $retorno['errorMsg'] = "Error: Nombre de usuario ya existe existente";
        return $retorno;
    }

    public function newUsuarioRol($data)
    {

        $resp = false;
        $existeRol = false;
        if (isset($data['idrol']) && isset($data['idusuario'])) {
            $abmUsRol = new AbmUsuarioRol();
            $col = $abmUsRol->buscar(['idusuario' => $data['idusuario']]);
            foreach ($col as $elem) {
                if ($elem->getObjRol()->getIdRol() == $data['idrol']) {
                    $existeRol = true;
                }
            }
        }
        if (!$existeRol) {

            $resp = $abmUsRol->alta($data);
        }
        if (!$resp) {

            $retorno['errorMsg'] = "Error: El usuario seleccionado ya tiene este rol";
        }
        $retorno['respuesta'] = $resp;
        return $retorno;
    }
}
