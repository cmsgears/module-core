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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Ownership Filter is an RBAC filter and works as child filter of RBAC filter. It is an additional
 * level of security and allows to execute controller action only if the context resource i.e. model
 * is owned by the currently logged in user. In all other cases, it ignore the action and throws
 * ForbiddenHttpException exception. The model must implement IOwner interface or provide isOwner method.
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
 *						'owner' => [
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
class OwnerFilter {

	public function doFilter( $args = [] ) {

		$typed = isset( $args[ 'typed' ] ) ? $args[ 'typed' ] : false;

		$user	= Yii::$app->user->identity;
		$model	= Yii::$app->controller->model; // Check whether model is already discoverd

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

			// Discover model using service if not done yet
			if( empty( $model ) ) {

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

						$model = $modelService->getBySlug( $slug );
					}
				}

				if( isset( $model ) ) {

					// Set controller primary model
					Yii::$app->controller->model = $model;
				}
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
