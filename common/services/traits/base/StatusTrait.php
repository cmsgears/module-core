<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * StatusTrait provide methods specific to models having status column, but does
 * not implement cmsgears\core\common\services\interfaces\base\IApproval.
 *
 * @since 1.0.0
 */
trait StatusTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// StatusTrait ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function checkStatusChange( $model, $oldStatus, $config = [] ) {

		$modelClass = static::$modelClass;

		if( $model->status !== intval( $oldStatus ) ) {

			$oldStatus	= $modelClass::$statusMap[ $oldStatus ];
			$newStatus	= $modelClass::$statusMap[ $model->status ];

			if( empty( $config[ 'template' ] ) ) {

				$config[ 'template' ] = CoreGlobal::TPL_NOTIFY_STATUS_CHANGE;
			}

			$config[ 'data' ][ 'parentType' ]	= $this->getParentTypeStr();
			$config[ 'data' ][ 'oldStatus' ]	= $oldStatus;
			$config[ 'data' ][ 'newStatus' ]	= $newStatus;
			$config[ 'data' ][ 'message' ]		= isset( $config[ 'data' ][ 'message' ] ) ? $config[ 'data' ][ 'message' ] : null;

			$this->notifyUser( $model, $config );

			return $model;
		}

		return false;
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// StatusTrait ---------------------------

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
