<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// CMG Imports
use cmsgears\core\common\models\entities\Site;


/**
 * The models having siteId column and supporting multi-site must use this trait.
 *
 * @property integer $siteId
 *
 * @since 1.0.0
 */
trait MultiSiteTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// MultiSiteTrait ------------------------

	/**
	 * Returns the corresponding site model.
	 *
	 * @return \cmsgears\core\common\models\entities\Site Site to which this model belongs.
	 */
	public function getSite() {

		return $this->hasOne( Site::class, [ 'id' => 'siteId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// MultiSiteTrait ------------------------

	/**
	 * @inheritdoc
	 *
	 * @return true Model using this trait supports multi-site.
	 */
	public static function isMultiSite() {

		return true;
	}

	// Read - Query -----------

	/**
	 * Return query to find the model with site.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with site.
	 */
	public static function queryWithSite( $config = [] ) {

		$config[ 'relations' ]	= [ 'site' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
