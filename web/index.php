<?php

// web/index.php

use Mate\Api\MateFactory;

$filename = __DIR__ . preg_replace( '#(\?.*)$#', '', $_SERVER['REQUEST_URI'] );
if ( php_sapi_name() === 'cli-server' && is_file( $filename ) ) {
	return false;
}

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->get(
	'/',
	function () {
		return "Some api... try /area/{area} where {area} is Berlin";
	}
);

$app->get(
	'/area/{area}',
	function ( $area ) {
		return json_encode( MateFactory::getInstance()->getAreaStore()->getAreaByName( $area ) );
	}
);

$app->run();