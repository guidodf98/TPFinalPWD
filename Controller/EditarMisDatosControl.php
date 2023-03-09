<?php
class EditarMisDatosControl {
  public function buscarUsuario() {
    $data = data_submitted();
    $usuario = null;

    if (isset($data['idu'])) {
      $controlAbmUsuario = new AbmUsuario();
      $condicion['idusuario'] = $data['idu'];

      if ($controlAbmUsuario->buscar($condicion)) {
        $usuario = $controlAbmUsuario->buscar($condicion)[0];
      }
    }

    return $usuario;
  }

  public function rolesDisponibles() {
    $sesion = new Session();
    $controlRol = new AbmRol();
    $usuario = $sesion->getObjUsuario();
    $usuarioRoles = $usuario->getColRoles();

    $allRoles = $controlRol->buscar(null);
    // mostrarArray($usuarioRoles);
    // mostrarArray($allRoles);
    if ($usuarioRoles[0]->getIdRol() != 1) {
      unset($allRoles[0]);
    }

    return $allRoles;
  }

  private function modificarRoles($usuario, $idUsuario, $data, $error) {
    $controlUsuarioRol = new AbmUsuarioRol();
    $rolesUsuario = $usuario->getColRoles();
    foreach ($rolesUsuario as $rolUsuario) {
      $baja = [
        'idusuario' => $idUsuario,
        'idrol' => $rolUsuario->getIdRol()
      ];
      $controlUsuarioRol->baja($baja);
    }

    if (isset($data['rol'])) {
      $rolesNuevos = $data['rol'];
      foreach ($rolesNuevos as $idRolNuevo => $nom) {
        $baja = ['idusuario' => $idUsuario, 'idrol' => $idRolNuevo];
        if (!$controlUsuarioRol->alta($baja)) {
          $error = 1;
        }
      }
    }
    return $error;
  }

  private function modificarPass($usuario, $data, $error) {
    if ($data['uspass'] != "null") {
      if ($data['uspass'] === $data['uspass2']) {
        $usuario->setUsPass(md5(intval($data['uspass'])));
      } else {
        $error = 2;
      }
    }
    return $error;
  }

  function accion($data) {
    $error = 0;

    $usuario = $this->buscarUsuario();

    if (isset($usuario) and isset($data['idu'])) {
      if ($data['idu'] == $usuario->getIdUsuario()) {
        $usuario->setUsNombre($data['usnombre']);
        $usuario->setUsMail($data['usmail']);

        $error = $this->modificarPass($usuario, $data, $error);
        $error = $this->modificarRoles($usuario, $data['idu'], $data, $error);

        $conAbmUsuario = new AbmUsuario();
        $datos = [
          'idusuario' => $usuario->getIdUsuario(),
          'usnombre' => $usuario->getUsNombre(),
          'uspass' => $usuario->getUsPass(),
          'usmail' => $usuario->getUsMail(),
          'usdeshabilitado' => $usuario->getUsDeshabilitado(),
        ];

        if (!$conAbmUsuario->modificacion($datos)) {
          $error = 3;
        };
      } else {
        $error = 4;
      }
    }

    return $error;
  }

  public function getUsuario() {
    $sesion = new Session();
    return $sesion->getObjUsuario();
  }

  public function mensajes($num) {
    $mensajes = [
      0 => 'Acción realizada con exito',
      1 => 'Hubo uno error al modificar el rol',
      2 => 'Las contraseñas no coinciden, por lo tanto no se han cambiado',
      3 => 'No se pudo actualizar los datos',
      4 => 'Este usuario no se puede modificar',
    ];
    return $mensajes[$num];
  }

}
