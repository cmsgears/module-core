<?php
namespace cmsgears\core\common\filters;

// Yii Imports
use Yii;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

// TODO: It can be further enhanced by adding support to discover using unique column name.

/**
 * Discover Filter finds and set the primary model using primary service of controller. In all other cases, it ignore the action and
 * throws ForbiddenHttpException exception.
 *
 * Ex:
 *	public function behaviors() {
 *
 *		...........
 *
 *		'actions' => [
 *			'<action name>' => [
 *				<permissions>,
 *				'filters' => [
 *					'discover' => [
 *						// We need to provided column name either as slug or id to discover primary model.
 *						'slug' or 'id' => '<slug>' or '<id>',
 *						// It's optional and required in case controller does not provide primary model service or a different service is required to discover primary model.
 *						'service' => '<factory name> or <service object>',
 *						// Optional and required in case slug param is different than slug
 *						'slugParam' => '<Slug Param>'
 *						// Optional and required in case id param is different than id
 *						'idParam' => '<Id Param>'
 *					]
 *				]
 *			]
 *		]
 *
 *		...........
 *	}
 */
class DiscoverFilter {

	public function doFilter( $args = [] ) {

		// Check whether model is typed
		$typed			= isset( $args[ 'typed' ] ) ? $args[ 'typed' ] : false;
		$type			= isset( $args[ 'type' ] ) ? $args[ 'type' ] : null;

		// Model to be discovered
		$model			= null;

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
			if( !isset( $args[ 'slug' ] ) || !$args[ 'slug' ] ) {

				$args[ 'id' ]	= true;
			}

			// Find model using id
			if( isset( $args[ 'id' ] ) && $args[ 'id' ] ) {

				$idParam	= 'id';

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

				if( $typed ) {

					if( isset( $type ) ) {

						$model	= $modelService->getBySlugType( $slug, $type );
					}
					else {

						$model	= $modelService->getBySlug( $slug, true ); // Get first one
					}
				}
				else {

					$model	= $modelService->getBySlug( $slug );
				}
			}

			// Apply Discover Filter
			if( isset( $model ) ) {

				return true;
			}
		}

		// Halt action execution in case model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
