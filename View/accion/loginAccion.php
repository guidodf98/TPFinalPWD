<?php include_once "../../config.php";

$control = new LoginControl();
$sesion = $control->logear();

if ($sesion != null && $sesion->getObjUsuario() != null) {
  if ($sesion->activa() and !$sesion->getObjUsuario()->getUsDeshabilitado()) {
    $rolesUsuario = $sesion->getColRoles();
    // echo "sesion activa y usuario sin rol";
    if (empty($rolesUsuario)) {
      header("Status: 301 Moved Permanently");
      header("Location: ../{$LOGIN}?error=3");
      $sesion->cerrar();
    } else {
      // echo "sesion activa y usuario con rol";
      $_SESSION['rol'] = $rolesUsuario[0]->getIdRol();
      header("Status: 301 Moved Permanently");
      header("Location: ../{$INICIO}");
    }
  } elseif ($sesion->getObjUsuario()->getUsDeshabilitado()) {
    // echo "sesion activa y usuario deshabilitado";
    header("Status: 301 Moved Permanently");
    header("Location: ../{$LOGIN}?error=2");
    $sesion->cerrar();
  }
} else {
  // echo "la contrase o usuario no coinciden";
  header("Status: 301 Moved Permanently");
  header("Location: ../{$LOGIN}?error=1");
}
