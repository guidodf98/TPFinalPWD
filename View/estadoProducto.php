<?php include_once "../config.php" ?>

<?php $title = 'Estado Producto';
include_once 'includes/head.php';
include_once 'includes/navbar.php';

$control = new EstadoProductoControl();
$productos = $control->listar();

?>


<div class="container d-flex justify-content-center align-items-start text-center mt-5">

  <?php if ($sesion->activa() && $sesion->getRolActual() == 2) { ?>

    <?php if (count($productos) > 0) { ?>
      <table class="table caption-top">

        <caption>
          <h1 class="pb-3 text-primary text-center">Estado Producto</h1>
        </caption>

        <thead>
          <tr>
            <th scope="col">Producto</th>
            <th scope="col">Marca</th>
            <th scope="col">Modelo</th>
            <th scope="col">Fecha de baja</th>
            <th scope="col">Dar de baja</th>
          </tr>
        </thead>

        <tbody>

          <?php $productos = ordenarArregloProductos($productos); ?>

          <?php foreach ($productos as $producto) {
            $textoDeshab = $control->textoDeshab($producto);
            $fotoDeshab = $control->fotoDeshab($producto);
            $modelo = $control->getModelo($producto);

            $dirImg = md5($producto->getIdProducto());
            $img = scandir($ROOT . "view/img/Productos/" . $dirImg)[2];
          ?>

            <tr>

              <td <?= $fotoDeshab ?>>
                <img class="<?= $fotoDeshab ?>" style="max-height:70px" src="<?= "./img/Productos/" . $dirImg . "/" . $img ?>">
              </td>

              <td <?= $textoDeshab ?>>
                <p class="mt-4"><?= $modelo ?></p>
              </td>

              <td <?= $textoDeshab ?>>
                <p class="mt-4"><?= $producto->getProNombre() ?></p>
              </td>

              <td <?= $textoDeshab ?>>
                <p class="mt-4"><?= $producto->getProDeshabilitado() ?></p>
              </td>


              <td>
                <?php if (!$producto->getProDeshabilitado()) { ?>
                  <a class="btn btn-danger mt-3" title="Dar de baja" href="accion/estadoProductoAccion.php?id=<?= $producto->getIdProducto() ?>" role="button"><i class="fas fa-trash-alt"></i></a>
                <?php } else { ?>
                  <a class="btn btn-warning mt-3" title="Reactivar" href="accion/estadoProductoAccion.php?id=<?= $producto->getIdProducto() ?>" role="button"><i class="fas fa-redo"></i></a>
                <?php } ?>
              </td>

            </tr>

          <?php } ?>
        </tbody>

      </table>

    <?php } else { ?>
      <div class="alert alert-danger d-flex align-items-center p-4" role="alert">
        <div>
          <h2>No hay usuarios cargados en el sistema</h2>
        </div>
      </div>
    <?php } ?>

  <?php } else { ?>
    <div class="alert alert-danger mt-20vh" role="alert">
      <h4 class="alert-heading">Esta pagina es solo para usuarios depositadores</h4>
    </div>
  <?php } ?>

</div>


<?php include_once 'includes/footer.php' ?>