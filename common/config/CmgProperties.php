<?php
namespace cmsgears\core\common\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\entities\SiteService;

class CmgProperties {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	/**
	 * The map stores all the core properties. It can be queried for all the available properties.
	 */
	protected $properties;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/*
	 * Initialise the properties from database.
	 */
	public function init( $configType ) {

		$siteService		= Yii::$app->factory->get( 'siteService' );
		$this->properties	= $siteService->getAttributeNameValueMapBySlugType( Yii::$app->core->getSiteSlug(), $configType );

		// Load main site properties
		if( Yii::$app->core->multiSite && count( $this->properties ) == 0 ) {

			$this->properties	= $siteService->getAttributeNameValueMapBySlugType( Yii::$app->core->getMainSiteSlug(), $configType );
		}
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// CmgProperties -------------------------

	// Properties

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