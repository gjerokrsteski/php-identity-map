<?php
require_once dirname(__FILE__).'/src/framework/RecursiveClassLoder.php';

spl_autoload_register(array(new RecursiveClassLoder(__DIR__, 'src/'), 'load'));
