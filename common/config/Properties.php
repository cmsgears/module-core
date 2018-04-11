<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\config;

// Yii Imports
use Yii;

/**
 * Properties is the base class for all the property classes having properties stored using
 * [[\cmsgears\core\common\models\resources\SiteMeta]].
 *
 * @since 1.0.0
 */
abstract class Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// TODO: Yii Cache is most appropriate solution
	protected static $typePropertyMap;

	/**
	 * The map stores all the properties. It can be queried for all the available properties.
	 */
	protected $properties;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	/*
	 * Initialise the site properties from database.
	 */
	public function init( $configType ) {

		$siteService = Yii::$app->factory->get( 'siteService' );

		$site = $siteService->getBySlug( Yii::$app->core->getSiteSlug() );

		if( Yii::$app->core->siteConfigAll ) {

			if( empty( self::$typePropertyMap ) ) {

				self::$typePropertyMap = [];

				$properties	= $siteService->getIdMetaMap( $site );

				foreach ( $properties as $property ) {

					$type = $property->type;

					if( !isset( self::$typePropertyMap[ $type ] ) ) {

						self::$typePropertyMap[ $type ] = [];
					}

					self::$typePropertyMap[ $type ][ $property->name ] = $property->value;
				}
			}

			$this->properties = self::$typePropertyMap[ $configType ];
		}
		else {

			$this->properties = $siteService->getMetaNameValueMapByMetaType( $site, $configType );
		}

		// Load main site properties in case child site does not have it's own properties
		// TODO: Load main site properties and override with child site properties
		if( Yii::$app->core->multiSite && count( $this->properties ) == 0 ) {

			$this->properties = $siteService->getMetaNameValueMapByMetaType( $site, $configType );
		}
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Properties ----------------------------

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
