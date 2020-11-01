<?php

php artisan tinker
$controller = app()->make('App\Http\Controllers\DataSourceController');
app()->call([$controller, 'process_all'], []);

