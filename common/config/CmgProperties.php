<?php
namespace cmsgears\core\common\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\services\SiteService;

class CmgProperties {

	/**
	 * The map stores all the core properties. It can be queried for all the available properties.
	 */
	protected $properties;

	/*
	 * Initialise the properties from database.
	 */
	public function init( $configType ) {

		$this->properties	= SiteService::getMetaMapBySlugType( Yii::$app->cmgCore->getSiteSlug(), $configType );

		// Load main site properties
		if( Yii::$app->cmgCore->multiSite && count( $this->properties ) == 0 ) {

			$this->properties	= SiteService::getMetaMapBySlugType( Yii::$app->cmgCore->getMainSiteSlug(), $configType );
		}
	}

	/**
	 * Return all the properties.
	 */
	public function getProperties() {

		return $this->properties;
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