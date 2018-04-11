<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\resources\IModelAnalyticsService;

use cmsgears\core\common\services\base\ModelResourceService;

/**
 * ModelAnalyticsService provide service methods of model analytics.
 *
 * @since 1.0.0
 */
class ModelAnalyticsService extends ModelResourceService implements IModelAnalyticsService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\ModelAnalytics';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelAnalyticsService -----------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateViewCount( $model, $views ) {

		$model->views = $views;

		return parent::update( $model, [
			'attributes' => [ 'views' ]
		]);
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelAnalyticsService -----------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
