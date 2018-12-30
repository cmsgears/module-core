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
use yii\helpers\ArrayHelper;

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

		$site		= Yii::$app->core->site;
		$siteSlug	= Yii::$app->core->siteSlug;

		if( Yii::$app->core->siteConfigAll ) {

			if( empty( self::$typePropertyMap ) ) {

				self::$typePropertyMap = [];

				$properties	= [];

				// Load main site properties and override with child site properties
				if( Yii::$app->core->multiSite && !( $siteSlug == CoreGlobal::SITE_MAIN ) ) {

					$mainSite = $siteService->getBySlug( CoreGlobal::SITE_MAIN );

					$mainProperties = $siteService->getIdMetaMap( $mainSite );
					$siteProperties = $siteService->getIdMetaMap( $site );

					$properties = ArrayHelper::merge( $mainProperties, $siteProperties );
				}
				// Load main site properties
				else {

					$properties	= $siteService->getIdMetaMap( $site );
				}

				foreach( $properties as $property ) {

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

			// Load main site properties and override with child site properties
			if( Yii::$app->core->multiSite && !( $siteSlug == CoreGlobal::SITE_MAIN ) ) {

				$mainSite = $siteService->getBySlug( CoreGlobal::SITE_MAIN );

				$mainProperties = $siteService->getMetaNameValueMapByMetaType( $mainSite, $configType );
				$siteProperties = $siteService->getMetaNameValueMapByMetaType( $site, $configType );

				$this->properties = ArrayHelper::merge( $mainProperties, $siteProperties );
			}
			// Load main site properties
			else {

				$this->properties = $siteService->getMetaNameValueMapByMetaType( $site, $configType );
			}
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

		return $this->properties[ $key ];
	}

}
