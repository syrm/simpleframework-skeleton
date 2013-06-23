<?php

session_start();

define('ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".."));

require_once ROOT . "/vendor/simpleframework/Notification.php";

simpleframework\Notification::init();

require_once ROOT . "/vendor/simpleframework/Kernel.php";

$env = file_get_contents(ROOT . "/app/config/env");

if (in_array($env, array('local', 'dev', 'preprod', 'prod')) === false) {
    $env = 'prod';
}

simpleframework\Notification::call('global', array($env));

$kernel = new simpleframework\Kernel();

if (PHP_SAPI === 'cli') {
    ini_set('max_execution_time', 0);

    if ($argc === 1) {
        echo "Help:\n";
        echo "\t-c controller";
        echo "\t-a action";
        echo "\t-p params (json format) (optionnal)";
        echo "\t-d subdir of controller (optionnal)";
        echo "\n";
        exit;
    }

    $options = array();
    for($i = 1; $i < count($argv); $i+=2) {
        $options[$argv[$i]] = $argv[$i+1];
    }

    if (isset($options['-c']) === false) {
        echo "where is -c ?\n";
        exit;
    }

    if (isset($options['-a']) === false) {
        echo "where is -a ?\n";
        exit;
    }

    $controller = $options['-c'];
    $action     = $options['-a'];

    if (isset($options['-p']) === false) {
        $params = array();
    } else {
        $params = json_decode($options['-p'], true);
    }

    if (isset($options['-d']) === false) {
        $dir = '/';
    } else {
        $dir = $options['-d'] . '/';
    }

    $kernel->startCli($env, $controller, $action, $params, $dir);
} else {
    $kernel->start($env);
}