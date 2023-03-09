<?php

include_once '../../config.php';

$control = new ListarDatosControl();
$retorno = $control->listarRoles(data_submitted());

echo json_encode($retorno);
