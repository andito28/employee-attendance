<?php

$version = config('base.conf.version');
$base = base_path("routes/features/web/$version/");

require($base . "auth.php");
require($base . "component.php");
