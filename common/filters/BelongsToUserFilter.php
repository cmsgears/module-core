<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\filters;

// Yii Imports
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * BelongsToUserFilter Filter can be used to verify the user ownership of entity or resource.
 *
 * It's simplified version of the OwnerFilter and need the model to declare belongsToUser
 * method to test the ownership.
 *
 * It requires Discover Filter to identify model in action.
 *
 * @since 1.0.0
 */
class BelongsToUserFilter {

	public function doFilter( $config = [] ) {

		$model	= Yii::$app->controller->model;
		$user	= Yii::$app->core->getUser();

		if( isset( $model ) && isset( $user ) ) {

			// Model belongs to User
			if( $model->belongsToUser( $user ) ) {

				return true;
			}

			// Not allowed
			throw new ForbiddenHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NO_ACCESS ) );
		}

		// Not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
