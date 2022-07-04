<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;


const ROOT_DIR = __DIR__.'/..';

(new Dotenv())->loadEnv(ROOT_DIR.'/.env');
