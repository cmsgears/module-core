<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\behaviors;

// Yii Imports
use Yii;
use yii\base\Behavior;
use yii\web\Controller;

/**
 * ActivityBehavior logs user activities of create, update and delete actions of
 * controller's primary model.
 *
 * @since 1.0.0
 */
class ActivityBehavior extends Behavior {

	public $admin = false;

	public $create = [];

	public $update = [];

	public $delete = [];

	public function events() {

		return [
			Controller::EVENT_AFTER_ACTION => 'log'
		];
	}

	public function log( $event ) {

		if( isset( $this->owner->model ) ) {

			$model		= $this->owner->model;
			$service	= $this->owner->modelService;
			$action		= $event->action->id;

			// No logging in absense of model
			if( !$model ) {

				return;
			}

			// Log create activity
			if( in_array( $action, $this->create ) ) {

				$action = isset( $this->create[ $action ] ) ? $this->create[ $action ] : $action;

				if( is_string(  $action ) ) {

					$config = [ 'admin' => $this->admin ];

					Yii::$app->eventManager->logCreate( $model, $service, $config );
				}
				else {

					$action[ 'admin' ] = $this->admin;

					Yii::$app->eventManager->logCreate( $model, $service, $action );
				}
			}
			// Log update activity
			else if( in_array( $action, $this->update ) ) {

				$action = isset( $this->create[ $action ] ) ? $this->create[ $action ] : $action;

				if( is_string(  $action ) ) {

					$config = [ 'admin' => $this->admin ];

					Yii::$app->eventManager->logUpdate( $model, $service, $config );
				}
				else {

					$action[ 'admin' ] = $this->admin;

					Yii::$app->eventManager->logUpdate( $model, $service, $action );
				}
			}
			// Log delete activity
			else if( in_array( $action, $this->delete ) ) {

				$action = isset( $this->create[ $action ] ) ? $this->create[ $action ] : $action;

				if( is_string(  $action ) ) {

					$config = [ 'admin' => $this->admin ];

					Yii::$app->eventManager->logDelete( $model, $service, $config );
				}
				else {

					$action[ 'admin' ] = $this->admin;

					Yii::$app->eventManager->logDelete( $model, $service, $action );
				}
			}
		}
	}

}
