<?php
include_once "../../config.php";

$control = new ModificarDatosControl();
$retorno = $control->modUsuario(data_submitted());

echo json_encode($retorno);
