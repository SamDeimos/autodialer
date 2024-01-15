<?php

/*-------------------------
Autor: Santiago Gutierrez
Web: xudo.dev
Mail: zam.2014.sg@gmail.com
---------------------------*/
date_default_timezone_set('America/Bogota');
// DB credentials.
define('DB_HOST', 'mariadb');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('DB_NAME', 'autodialer');

define('DB_HOST_ASTERISK', 'mariadb');
define('DB_USER_ASTERISK', 'root');
define('DB_PASS_ASTERISK', 'password');
define('DB_NAME_ASTERISK', 'asterisk');

/********** Conexión Autodialer **********/
try {
    $conAutodialer = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
} catch (Throwable $th) {
    echo "Error: " . $th;
}

try {
    $conAstetisk = @mysqli_connect(DB_HOST_ASTERISK, DB_USER_ASTERISK, DB_PASS_ASTERISK, DB_NAME_ASTERISK);
} catch (Throwable $th) {
    echo "Error: " . $th;
}
