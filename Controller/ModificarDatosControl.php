<?php

class ModificarDatosControl
{
  public function modUsuario($data)
  {
    $resp = false;
    if (isset($data['idusuario']) && isset($data['usnombre']) && isset($data['uspass']) && isset($data['usmail'])) {
      if ($data['usdeshabilitado'] == "null") {
        $data['usdeshabilitado'] = null;
      }
      $abmUs = new AbmUsuario();
      $resp = $abmUs->modificacion($data);
    }
    if (!$resp) {

      $retorno['errorMsg'] = "fallo la modificacion!!!";
    }
    $retorno['respuesta'] = $resp;
    return $retorno;
  }

  public function modMenu($data)
  {
    $resp = false;
    if (isset($data['idmenu'])) {
      if ($data['medeshabilitado'] == 'null') {
        $data['medeshabilitado'] = null;
      }
      $abmMenu = new AbmMenu;
      $data = $abmMenu->buscarIdPadre($data);
      $resp =  $abmMenu->modificacion($data);
    }
    if (!$resp) {
      $msj = "la modificacion fallo";
    }
    $retorno['respuesta'] = $resp;
    return $retorno;
  }

  public function modRol($data)
  {
    $resp = false;
    if (isset($datos['idrol']) && isset($datos['rodescripcion'])) {
      $abmRol = new AbmRol();
      $resp = $abmRol->modificacion($datos);
    }
    if (!$resp) {

      $retorno['errorMsg'] = "error: Fallo la modificacion del rol";
    }

    $retorno['respuesta'] = $resp;
  }
}
