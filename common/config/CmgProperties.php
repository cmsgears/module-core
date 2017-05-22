<?php
namespace cmsgears\core\common\config;

// Yii Imports
use \Yii;

use cmsgears\core\common\services\entities\SiteService;

class CmgProperties {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// TODO: Yii Cache is most appropriate solution
	protected static $typePropertyMap;

	/**
	 * The map stores all the properties. It can be queried for all the available properties.
	 */
	protected $properties;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/*
	 * Initialise the site properties from database.
	 */
	public function init( $configType ) {

		$siteService	= Yii::$app->factory->get( 'siteService' );

		if( Yii::$app->core->siteConfigAll ) {

			if( empty( self::$typePropertyMap ) ) {

				self::$typePropertyMap	= [];

				$properties	= $siteService->getIdMetaMapBySlug( Yii::$app->core->getSiteSlug() );

				foreach ( $properties as $property ) {

					$type = $property->type;

					if( !isset( self::$typePropertyMap[ $type ] ) ) {

						self::$typePropertyMap[ $type ] = [];
					}

					self::$typePropertyMap[ $type ][ $property->name ] = $property->value;
				}
			}

			$this->properties	= self::$typePropertyMap[ $configType ];
		}
		else {

			$this->properties	= $siteService->getMetaNameValueMapBySlugType( Yii::$app->core->getSiteSlug(), $configType );
		}

		// Load main site properties in case child site does not have it's own properties
		// TODO: Load main site properties and override with child site properties
		if( Yii::$app->core->multiSite && count( $this->properties ) == 0 ) {

			$this->properties	= $siteService->getMetaNameValueMapBySlugType( Yii::$app->core->getMainSiteSlug(), $configType );
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
