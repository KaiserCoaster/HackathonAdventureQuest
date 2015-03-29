<?php

$f3=require('lib/base.php');

$f3->config('app/config.ini');

$f3->route('GET /', 'App->home');

$f3->run();

?>