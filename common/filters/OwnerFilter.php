<?php
namespace cmsgears\core\common\filters;

// Yii Imports
use Yii;
use yii\web\ForbiddenHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Ownership Filter is additional level of security and allows to execute controller action only if the 
 * context resource i.e. model is owned by the current user. In all other cases, it ignore the action and 
 * throws ForbiddenHttpException exception. The model must implement IOwner interface or provide isOwner method.
 * 
 * Ex:
 *	public function behaviors() {
 * 
 * 		...........
 * 
 *		'actions' => [
 *			'<action name>' => [ <permissions>, 
 * 									'filters' => [ 
 * 										'owner' => [ 
 * 											'slug' or 'id' => '<slug>' or '<id>',	// We need to provided either of them 
 * 											'service' => '<classpath> or <model>', 	// It's optional and required in case controller do not provide model service
 * 											'slugParam' => '<Slug Param>'			// Optional and required in case slug param is different than slug
 * 											'idParam' => '<Id Param>'			// Optional and required in case id param is different than id
 * 										]
 * 									]
 * 								]
 * 		]
 * 
 *  	...........
 * 	}
 */
class OwnerFilter {

	public function doFilter( $args = [] ) {

		$user	= Yii::$app->user->identity;
		$model	= null;

		// Find Service
		$modelService	= null;

		if( isset( $args[ 'service' ] ) ) {

			if( is_string( $args[ 'service' ] ) ) {

				$modelService = Yii::createObject( $args[ 'service' ] );
			}
			else {

				$modelService = $args[ 'service' ];
			}
		}
		else {

			$modelService	= Yii::$app->controller->modelService;
		}

		// Proceed further if service found
		if( isset( $modelService ) ) {

			if( isset( $args[ 'id' ] ) && $args[ 'id' ] ) {
	
				$idParam		= 'id';
	
				if( isset( $args[ 'idParam' ] ) ) {
	
					$idParam	= $args[ 'idParam' ];
				}

				$id		= Yii::$app->request->get( $idParam );
				$model	= $modelService::findById( $id );
			}
			else if( isset( $args[ 'slug' ] ) && $args[ 'slug' ] ) {

				$slugParam	= 'slug';

				if( isset( $args[ 'slugParam' ] ) ) {
	
					$slugParam	= $args[ 'slugParam' ];
				}

				$slug	= Yii::$app->request->get( $slugParam );
				$model	= $modelService::findBySlug( $slug );
	
				if( !isset( $model ) || !$model->isOwner( $user ) ) {
	
					// Not Allowed
					throw new ForbiddenHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
				}
			}

			if( isset( $model ) && $model->isOwner( $user ) ) {

				return true;
			}
		}

		// Not Allowed
		throw new ForbiddenHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
	}
}

?>