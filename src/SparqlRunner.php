<?php

namespace Mate\Api;

use Symfony\Component\HttpKernel\Client;

class SparqlRunner {

	/**
	 * @var Client
	 */
	private $client;
	
	private $sparqlurl;

	public function __construct( $client, $sparqlUrl ) {
		$this->client = $client;
		$this->sparqlurl = $sparqlUrl;
	}

	public function run( $query ) {
		$sparqlResponse = $this->client->get(
			$this->sparqlurl . '?format=json&query=' . urlencode( $query )
		);
		$sparqlArray = json_decode( $sparqlResponse->getBody(), true );
		return $sparqlArray;
	}

}