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
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Discover Filter is an RBAC filter and works as child filter of RBAC filter. It
 * finds and set the primary model using primary service of controller. In all other
 * cases, it ignore the action and throws ForbiddenHttpException exception. It must
 * be the first filter in case an action is configured for multiple filters. The
 * subsequent filters can use the primary model identified by it.
 *
 * Ex:
 *	public function behaviors() {
 *
 *		...........
 *
 *		'rbac' => [
 *			'actions' => [
 *				'<action name>' => [
 *					<permissions>,
 *					'filters' => [
 *						'discover' => [
 *							// Optional and required in case controller does not provide primary model service or a different service is required to discover primary model.
 *							'service' => '<factory name> or <service object>',
 *							// Optional and required in case slug param is different than slug
 *							'slugParam' => '<Slug Param>',
 *							// Optional and required in case id param is different than id
 *							'idParam' => '<Id Param>',
 *							// Optional and required to explicitly specify type column
 *							'typed' => true or false
 *						]
 *					]
 *				]
 *			]
 *		]
 *
 *		...........
 *	}
 *
 * @since 1.0.0
 */
class DiscoverFilter {

	public function doFilter( $args = [] ) {

		// Model to be discovered
		$model = Yii::$app->controller->model;

		// Model is already discovered
		if( isset( $model ) ) {

			return true;
		}

		// Typed
		$typed = isset( $args[ 'typed' ] ) ? $args[ 'typed' ] : false;

		// Find Service
		$modelService = null;

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

			$modelService = Yii::$app->controller->modelService;
		}

		// Proceed further if service found
		if( isset( $modelService ) ) {

			$slugParam = 'slug';

			if( isset( $args[ 'slugParam' ] ) ) {

				$slugParam = $args[ 'slugParam' ];
			}

			$slug = Yii::$app->request->get( $slugParam );

			// Use default column as id
			if( empty( $slug ) ) {

				$args[ 'id' ] = true;
			}

			// Find model using id
			if( isset( $args[ 'id' ] ) && $args[ 'id' ] ) {

				$idParam = 'id';

				if( isset( $args[ 'idParam' ] ) ) {

					$idParam = $args[ 'idParam' ];
				}

				$id		= Yii::$app->request->get( $idParam );
				$model	= $modelService->getById( $id );
			}
			// Find model using slug
			else if( isset( $slug ) ) {

				// Flag to check typed models
				$typed	= !$typed ? $modelService->isTyped() : false;
				$type	= $typed ? Yii::$app->request->get( 'type', null ) : null;

				// Model uses SlugTypeTrait
				if( $typed ) {

					// Notes: Make sure that the Model support unique validator either for slug or slug and type
					if( isset( $type ) ) {

						$model = $modelService->getBySlugType( $slug, $type );
					}
					// Notes: Make sure that the Model support unique validator for slug
					else {

						$model = $modelService->getFirstBySlug( $slug );
					}
				}
				else {

					// Notes: Make sure that the Model support unique validator for slug
					$model = $modelService->getBySlug( $slug );
				}
			}

			// Apply Discover Filter
			if( isset( $model ) ) {

				// Set controller primary model
				Yii::$app->controller->model = $model;

				return true;
			}
		}

		// Halt action execution in case model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
