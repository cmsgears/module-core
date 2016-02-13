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

			$slugParam		= 'slug';

			if( isset( $args[ 'slugParam' ] ) ) {

				$slugParam	= $args[ 'slugParam' ];
			}

			$modelService	= Yii::createObject( $args[ 'service' ] );
			$slug			= Yii::$app->request->get( $slugParam );
			$model			= $modelService::findBySlug( $slug );

			if( !isset( $model ) || !$model->isOwner( $user ) ) {

				// Not Allowed
				throw new ForbiddenHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
			}
		}

		if( isset( $args[ 'id' ] ) && $args[ 'id' ] ) {

			$idParam		= 'id';

			if( isset( $args[ 'idParam' ] ) ) {

				$idParam	= $args[ 'idParam' ];
			}

			$modelService	= Yii::createObject( $args[ 'service' ] );
			$id				= Yii::$app->request->get( $idParam );
			$model			= $modelService::findById( $id );

			if( !isset( $model ) || !$model->isOwner( $user ) ) {

				// Not Allowed
				throw new ForbiddenHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
			}
		}
	}
}

?>