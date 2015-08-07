<?php
namespace cmsgears\core\common\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\services\SiteService;

abstract class CmgProperties {

	/**
	 * The map stores all the core properties. It can be queried for all the available properties.
	 */
	protected $properties;

	/*
	 * Initialise the properties from database.
	 */
	public function init( $configType ) {

		$this->properties	= SiteService::getMetaMapByNameType( Yii::$app->cmgCore->getSiteName(), $configType );

		// Load main site properties
		if( Yii::$app->cmgCore->multiSite && count( $this->properties ) == 0 ) {

			$this->properties	= SiteService::getMetaMapByNameType( Yii::$app->cmgCore->getMainSiteName(), $configType );
		}
	}

	/**
	 * Return core property for the specified key.
	 */
	public function getProperty( $key ) {

		$findKey	= "$key";

		return $this->properties[ $findKey ];
	}
}

?>