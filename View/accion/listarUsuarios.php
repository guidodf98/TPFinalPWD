<?php

include_once '../../config.php';

$control = new ListarDatosControl();
$retorno = $control->listarUsuarios();

echo json_encode($retorno);
