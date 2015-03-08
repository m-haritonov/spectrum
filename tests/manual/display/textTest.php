<?php
header('content-type: text/plain; charset=utf-8');
require_once __DIR__ . '/../../init.php';
config::setOutputFormat('text');
require __DIR__ . '/test.php';