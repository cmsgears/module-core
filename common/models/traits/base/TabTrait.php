<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// Yii Import
use Yii;

/**
 * The tabs to show data in tabs. It reads the controller action and find the active, next
 * and previous tabs.
 *
 * The models using this trait must initiate the tab arrays using init method.
 *
 */
trait TabTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * Different scenarios can follow different tab status. In such cases, the model must
	 * initiate the appropriate tabs based on given scenario.
	 */
	public $tabScenario;

	// Protected --------------

	// Private ----------------

	protected $tabs			= [];

	protected $tabStatus	= [];

	protected $nextStatus	= [];

	protected $previousTab	= [];

	protected $nextTab		= [];

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// TabTrait ------------------------------

	/**
	 * @inheritdoc
	 */
	public function getTab( $status = null ) {

		if( isset( $status ) && isset( $this->tabs[ $status ] ) ) {

			return $this->tabs[ $status ];
		}
		else if( isset( $this->tabs[ $this->status ] ) ) {

			return $this->tabs[ $this->status ];
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getTabStatus() {

		$action	= Yii::$app->controller->action->id;

		if( isset( $this->tabStatus[ $action ] ) ) {

			return $this->tabStatus[ $action ];
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getNextStatus( $status = null ) {

		if( !isset( $status ) ) {

			$status	= $this->status;
		}

		if( isset( $this->nextStatus[ $status ] ) ) {

			return $this->nextStatus[ $status ];
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getPreviousTab() {

		$action		= Yii::$app->controller->action->id;
		$baseUrl	= isset( Yii::$app->controller->baseUrl ) ? Yii::$app->controller->baseUrl : null;

		if( isset( $this->previousTab[ $action ] ) ) {

			if( isset( $baseUrl ) ) {

				$tab = $this->previousTab[ $action ];

				if( isset( $this->slug ) ) {

					return "$baseUrl/$tab?slug=$this->slug";
				}
				else {

					return "$baseUrl/$tab?id=$this->id";
				}
			}
			else {

				return $this->previousTab[ $action ];
			}
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getNextTab() {

		$action		= Yii::$app->controller->action->id;
		$baseUrl	= isset( Yii::$app->controller->baseUrl ) ? Yii::$app->controller->baseUrl : null;

		if( isset( $this->nextTab[ $action ] ) ) {

			if( isset( $baseUrl ) ) {

				$tab = $this->nextTab[ $action ];

				if( isset( $this->slug ) ) {

					return "/{$baseUrl}/$tab?slug=$this->slug";
				}
				else {

					return "/{$baseUrl}/$tab?id=$this->id";
				}
			}
			else {

				return $this->nextTab[ $action ];
			}
		}

		return null;
	}

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
