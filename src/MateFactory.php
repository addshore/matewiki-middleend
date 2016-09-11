<?php

namespace Mate\Api;

use GuzzleHttp\Client;

class MateFactory {

	/**
	 * @config
	 * @var array
	 */
	private $areaToItemMap = array(
		'berlin' => 'Q108258'
	);

	/**
	 * @config
	 * @var string
	 */
	private $sparqlUrl = 'http://sparql.librarybase.wmflabs.org/bigdata/namespace/lb/sparql';

	private static $instance;

	/**
	 * @return MateFactory
	 */
	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * @config
	 * @return SparqlRunner
	 */
	public function getSparqlRunner(){
		return new SparqlRunner(
			$this->getClient(),
			$this->sparqlUrl
		);
	}

	public function getAreaStore(){
		return new AreaStore(
			$this->getSparqlRunner(),
			$this->areaToItemMap
		);
	}

	public function getClient() {
		return new Client();
	}

}