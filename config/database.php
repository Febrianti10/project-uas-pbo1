<?php
return [
    'host'     => getenv('MYSQLHOST') ?: 'localhost',  // ← ini fallback ke localhost
    'port'     => getenv('MYSQLPORT') ?: '3306',       
    'dbname'   => getenv('MYSQLDATABASE') ?: 'sip_hewan', // ← nama database lokal
    'username' => getenv('MYSQLUSER') ?: 'root',       // ← user lokal
    'password' => getenv('MYSQLPASSWORD') ?: '',       // ← password kosong (lokal)
    'charset'  => 'utf8mb4'
];
