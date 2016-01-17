<?php
namespace cmsgears\core\common\filters;

// Yii Imports
use Yii;
use yii\web\ForbiddenHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class OwnerFilter {

	public function doFilter( $args = [] ) {

		$user	= Yii::$app->user->identity;

		if( isset( $args[ 'slug' ] ) && $args[ 'slug' ] ) {

			$modelService	= Yii::createObject( $args[ 'service' ] );
			$slug			= Yii::$app->request->get( 'slug' );
			$model			= $modelService::findBySlug( $slug );

			if( !isset( $model ) || !$model->isOwner( $user ) ) {

				// Not Allowed
				throw new ForbiddenHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
			}
		}
	}
}

?>