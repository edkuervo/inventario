<?php

$arr_clientes = array('nombre'=>'Jose', 'edad' => '20');

$json_string = json_encode($arr_clientes);
$file = 'clientes.json';
file_put_contents($file, $json_string);

?>