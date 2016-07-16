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
 * 											'slug' or 'id' => '<slug>' or '<id>',	// We need to provided either of them to apply ownership filter
 * 											'service' => '<factory name> or <service object>', 	// It's optional and required in case controller do not provide model service
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

		// Use service provided exclusively for the filter
		if( isset( $args[ 'service' ] ) ) {

			// Factory name of service
			if( is_string( $args[ 'service' ] ) ) {

				$modelService = Yii::$app->factory->get( $args[ 'service' ] );
			}
			// Service object
			else {

				$modelService = $args[ 'service' ];
			}
		}
		// Try to find service in controller member variables
		else if( Yii::$app->controller->hasProperty( 'modelService' ) ) {

			$modelService	= Yii::$app->controller->modelService;
		}

		// Proceed further if service found
		if( isset( $modelService ) ) {

			$model	= null;

			// Use default column as id
			if( !isset( $args[ 'slug' ] ) ) {

				$args[ 'id' ]	= true;
			}

			// Find model using id
			if( isset( $args[ 'id' ] ) && $args[ 'id' ] ) {

				$idParam		= 'id';

				if( isset( $args[ 'idParam' ] ) ) {

					$idParam	= $args[ 'idParam' ];
				}

				$id		= Yii::$app->request->get( $idParam );
				$model	= $modelService->getById( $id );
			}
			// Find model using slug
			else if( isset( $args[ 'slug' ] ) && $args[ 'slug' ] ) {

				$slugParam	= 'slug';

				if( isset( $args[ 'slugParam' ] ) ) {

					$slugParam	= $args[ 'slugParam' ];
				}

				$slug	= Yii::$app->request->get( $slugParam );
				$model	= $modelService->getBySlug( $slug );
			}

			// Apply owner filter
			if( isset( $model ) && $model->hasMethod( 'isOwner' ) && $model->isOwner( $user ) ) {

				return true;
			}
			else {

				throw new ForbiddenHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
			}

			return true;
		}

		// Halt action execution in case a valid service is not found
		throw new ForbiddenHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
	}
}
