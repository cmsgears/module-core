<?php
namespace cmsgears\core\common\models\traits;

// Yii Import
use Yii;

/**
 * The tabs to show data in tabs. It reads the controller action and find the active, next and previous tabs.
 *
 * The models using this trait must initiate the tab arrays using Yii's init method.
 *
 */
trait TabTrait {

	// Different scenarios can follow different tab status. In such cases, the model must initiate the appropriate tabs based on given scenario.
	public $tabScenario;

	private $tabStatus		= [];

	private $nextStatus		= [];

	private $previousTab	= [];

	private $nextTab		= [];

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// TabTrait ------------------------------

	public function getTabStatus() {

		$action	= Yii::$app->controller->action->id;

		if( isset( $this->tabStatus[ $action ] ) ) {

			return $this->tabStatus[ $action ];
		}

		return null;
	}

	public function getNextStatus( $status = null ) {

		if( !isset( $status ) ) {

			$status	= $this->status;
		}

		if( isset( $this->nextStatus[ $status ] ) ) {

			return $this->nextStatus[ $status ];
		}

		return null;
	}

	public function getPreviousTab() {

		$action		= Yii::$app->controller->action->id;
		$basePath	= isset( Yii::$app->controller->basePath ) ? Yii::$app->controller->basePath : null;

		if( isset( $this->previousTab[ $action ] ) ) {

			if( isset( $basePath ) ) {

				$tab = $this->previousTab[ $action ];

				if( isset( $this->slug ) ) {

					return "$basePath/$tab?slug=$this->slug";
				}
				else {

					return "$basePath/$tab?id=$this->id";
				}
			}
			else {

				return $this->previousTab[ $action ];
			}
		}

		return null;
	}

	public function getNextTab() {

		$action		= Yii::$app->controller->action->id;
		$basePath	= isset( Yii::$app->controller->basePath ) ? Yii::$app->controller->basePath : null;

		if( isset( $this->nextTab[ $action ] ) ) {

			if( isset( $basePath ) ) {

				$tab = $this->nextTab[ $action ];

				if( isset( $this->slug ) ) {

					return "$basePath/$tab?slug=$this->slug";
				}
				else {

					return "$basePath/$tab?id=$this->id";
				}
			}
			else {

				return $this->nextTab[ $action ];
			}
		}

		return null;
	}

	// Validators -------------

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// TabTrait ------------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
