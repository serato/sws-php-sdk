<?php
use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
;
return new Sami($iterator, array(
    'title'     => 'Serato SWS PHP SDK',
    'build_dir' => __DIR__ . '/docs/php',
    'cache_dir' => __DIR__ . '/docs/cache'
));
