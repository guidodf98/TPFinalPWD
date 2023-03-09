<?php include_once "../config.php";

$title = 'Actualizar Datos de Usuario';
include_once 'includes/head.php';
include_once 'includes/navbar.php';

$data = data_submitted();

if ($sesion->activa()) {
  $control = new EditarMisDatosControl();
  $rolesDisponibles = $control->rolesDisponibles();
  $usuario = $control->getUsuario();
  $usuarioRoles = $usuario->getColRoles();
?>


  <!-- NOTIFICACION -->
  <?php if (isset($data['m'])) { ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <?= $control->mensajes($data['m']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php } ?>

  <?php if ($usuario) { ?>
    <form id="form-actualizar-login" class="needs-validation p-5 w-50 mx-auto mt-20vh" action="./accion/editarMisDatosAccion.php" method="post" novalidate>

      <input type="hidden" name="idu" value="<?= $usuario->getIdUsuario() ?>">

      <!-- Usuario -->
      <div class="form-group mb-3">
        <label for="usnombre">Nombre de usuario</label>
        <input type="text" name="usnombre" value="<?= $usuario->getUsNombre() ?>" class="form-control" id="usnombre" required>
      </div>

      <!-- Mail -->
      <div class="form-group mb-3">
        <label for="usmail">Mail</label>
        <input type="email" name="usmail" value="<?= $usuario->getUsMail() ?>" class="form-control" id="usmail" required>
      </div>

      <?php if ($usuarioRoles[0]->getIdRol() != 3) { ?>
        <!-- Roles -->
        <div class="form-group mb-3">
          <label for="usmail">Roles</label>
          <div class="form-group mb-3">
            <?php
            foreach ($rolesDisponibles as $i => $rol) {
              $checked = "";
              foreach ($usuarioRoles as $rolUsuario) {
                if ($checked == "" and $rol->getIdRol() == $rolUsuario->getIdRol()) {
                  $checked = "checked";
                }
              }
            ?>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" <?= $checked ?> id="rol-<?= $rol->getRoDescripcion() ?>" name="rol[<?= $rol->getIdRol() ?>]" value="<?= $rol->getRoDescripcion() ?>">
                <label class="form-check-label" for="rol-<?= $rol->getRoDescripcion() ?>"><?= $rol->getRoDescripcion() ?></label>
              </div>

            <?php
              unset($rolesDisponibles[$i]);
            }
            ?>
          </div>
        </div>
      <?php } else { ?>
        <input type="hidden" name="rol[3]" value="3">
      <?php } ?>
      <!-- Contraseña -->
      <button type="button" onclick="togglePass()" class="btn btn-primary btn-sm" id="pass-button-principal">Cambiar contraseña</button>

      <div class="form-group mb-3 pass-input d-none">
        <label for="uspass">Contraseña</label>
        <input type="password" name="uspass" class="form-control" id="uspass" pattern="^[0-9]*$">
      </div>

      <div class="form-group mb-3 pass-input d-none">
        <label for="uspass2">Confirmar contraseña</label>
        <input type="password" name="uspass2" class="form-control" id="uspass2" pattern="^[0-9]*$">
      </div>

      <button type="button" onclick="togglePass()" class="btn btn-secondary btn-sm d-none" id="pass-button-secundario">Cancelar</button>


      <!-- Registrar -->
      <div class="text-center">
        <button type="submit" class="btn btn-primary mt-3">Registrar</button>
      </div>

    </form>

  <?php } ?>

<?php } else { ?>
  <div class="container d-flex justify-content-center align-items-start text-center mt-5">
    <div class="alert alert-danger mt-20vh" role="alert">
      <h4 class="alert-heading">Esta pagina es solo para usuarios registrados</h4>
    </div>
  </div>
<?php } ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="js/validation.js"></script>
<script src="js/togglePass.js"></script>


<?php include_once 'includes/footer.php' ?>