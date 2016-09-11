<?php

namespace Mate\Api;

class AreaStore {

	/**
	 * @var SparqlRunner
	 */
	private $queryRunner;

	public function __construct( SparqlRunner $queryRunner, array $areaToItemMap = array() ) {
		$this->queryRunner = $queryRunner;
		$this->areaToItemMap = $areaToItemMap;
	}
	
	public function getAreaByName( $name ) {
		if( !array_key_exists( strtolower( $name ), $this->areaToItemMap ) ) {
			return array();
		}

		$query = file_get_contents( __DIR__ . '/../sparql/areaByName.sparql' );
		$areaItemId = $this->areaToItemMap[strtolower( $name )];
		$query = str_replace( '$1', $areaItemId, $query );
		$resultArray = $this->queryRunner->run( $query );

		$area = array(
			'name' => ucfirst( $name ),
			'locations' => array(),
			//TODO people?
			'people' => array(),
		);

		foreach( $resultArray['results']['bindings'] as $resultBinding ) {
			$key = $resultBinding['item']['value'];
			if ( !array_key_exists( $key, $area['locations']) ) {
				$area['locations'][$key] = array();
			}
			// Every item should have a label
			$area['locations'][$key]['name'] = $resultBinding['itemLabel']['value'];

			if( $resultBinding['itemDescription']['value'] !== null ) {
				$area['locations'][$key]['description'] = $resultBinding['itemDescription']['value'];
			}
			if( $resultBinding['websiteString']['value'] !== null ) {
				$area['locations'][$key]['website'] = $resultBinding['websiteString']['value'];
			}
			if( $resultBinding['contact']['value'] !== null ) {
				$area['locations'][$key]['contact'] = $resultBinding['contact']['value'];
			}
			if( $resultBinding['tagsLabel']['value'] !== null ) {
				if ( !array_key_exists( 'tags',  $area['locations'][$key] ) ) {
					$area['locations'][$key]['tags'] = array();
				}
				$area['locations'][$key]['tags'][] = $resultBinding['tagsLabel']['value'];
			}
			if( $resultBinding['coords']['value'] !== null ) {
				list( $lat, $lon ) = explode( ' ', trim( str_replace( 'Point', '', $resultBinding['coords']['value'] ), '()' ) );
				$area['locations'][$key]['lat'] = $lat;
				$area['locations'][$key]['lon'] = $lon;
			}
		}

		// Remove the keys we used.
		$area['locations'] = array_values( $area['locations'] );

		return $area;
	}
}