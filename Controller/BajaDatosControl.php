<?php

class BajaDatosControl
{
    public function bajaMenu($data)
    {
        $resp = false;
        if (isset($data['idmenu'])) {
            if ($data['medeshabilitado'] == "null") {
                $data['medeshabilitado'] = date("Y-m-d H:i:s");
            } else {
                $data['medeshabilitado'] = null;
            }
            $abmMenu = new AbmMenu;
            $data = $abmMenu->buscarIdPadre($data);
            $resp = $abmMenu->modificacion($data);
        }

        if (!$resp) {
            $retorno['errorMsg'] = "No se elinmino el registro";
        }
        $retorno['respuesta'] = $resp;
        return $retorno;
    }
    public function bajaMenuRol($data)
    {
        $resp = false;
        if (isset($data['idrol']) && isset($data['idmenu'])) {
            $abm = new AbmMenuRol;
            $resp = $abm->baja($data);
        }
        if (!$resp) {
            $retorno['errorMsj'] = "ERROR: No se puede eliminar rol al menu ";
        }
        $retorno['respuesta'] = $resp;
    }
    public function bajaRol($data)
    {
        $resp = true;

        if (isset($data['idrol'])) {
            $abmUsRol = new AbmUsuarioRol;
            $colUsuarioRol = $abmUsRol->buscar(['idrol' => $data['idrol']]);
            if (count($colUsuarioRol) != 0) {
                foreach ($colUsuarioRol as $usRol) {
                    $arr['idusuario'] = $usRol->getObjUsuario()->getIdUsuario();
                    $arr['idrol'] = $usRol->getObjRol()->getIdRol();

                    $resp = $abmUsRol->baja($arr);
                }
            }
            if ($resp) {

                $abmMenuRol = new AbmMenuRol;
                $colMenuRol = $abmMenuRol->buscar(['idrol' => $data['idrol']]);
                if (count($colMenuRol) != 0) {
                    foreach ($colMenuRol as $menuRol) {
                        $arr1['idmenu'] = $menuRol->getObjMenu()->getIdMenu();
                        $arr1['idrol'] = $menuRol->getObjRol()->getIdRol();
                        $resp = $abmMenuRol->baja($arr1);
                    }
                }
            }
            if ($resp) {
                $abmRol = new AbmRol;
                $resp = $abmRol->baja(["idrol" => $data['idrol']]);
            }
        };

        if (!$resp) {

            $retorno['errorMsg'] = "ERROR: No se elimino el rol";
        }
        $retorno['respuesta'] = $resp;
        return $retorno;
    }
    public function bajaRolUsuario($data)
    {
        $resp = false;
        if (isset($data['idrol']) && isset($data['idusuario'])) {
            $abmUsRol = new AbmUsuarioRol;
            $resp = $abmUsRol->baja($data);
        }
        if (!$resp) {
            $retorno['errorMsj'] = "ERROR: No se puede eliminar el rol se usuarui ";
        }
        $retorno['respuesta'] = $resp;
        return $retorno;
    }
    public function bajaUsuario($data)
    {
        $resp = false;
        if ($data['usdeshabilitado'] == "null") {
            $data['usdeshabilitado'] = date('Y-m-d H:i:s');
        } else {
            $data['usdeshabilitado'] = null;
        }
        $abmUsuario = new AbmUsuario();
        $resp = $abmUsuario->modificacion($data);
        if (!$resp) {
            $retorno['errorMsg'] = "error al dar de baja el usuario wey";
        }
        $retorno['respuesta'] = $resp;
        return $retorno;
    }
}
