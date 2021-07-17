<?php

//require "vendor/autoload.php";

use PhpCsFixer\Config;

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
//    ->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in(__DIR__)
;

$config = new Config();

return $config
    ->setRules([
        '@PSR12' => true,
//        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
    ;

?>