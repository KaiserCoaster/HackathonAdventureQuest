<?php

$f3=require('lib/base.php');

$f3->config('app/config.ini');

$f3->route('GET /', 'App->home');

$f3->route('GET /@id', 'App->quest');

$f3->route('GET /make/@parent/@option', 'App->make');

$f3->route('POST /make/@parent/@option', 'App->makePost');

$f3->run();

?>