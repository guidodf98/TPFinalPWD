<?php
include_once "../../config.php";

array_map('unlink', glob($ROOT . "View/img/Productos/" . md5(data_submitted()['id']) . "/*.jpg"));

echo "<script type='text/javascript'>";
echo "window.close();";
echo "</script>";
