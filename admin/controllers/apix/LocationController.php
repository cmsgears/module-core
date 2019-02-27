<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * LocationController provides actions to get options list and search results of province,
 * region and city.
 *
 * @since 1.0.0
 */
class LocationController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission = CoreGlobal::PERM_ADMIN;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'province-options' => [ 'permission' => $this->crudPermission ],
					'region-options' => [ 'permission' => $this->crudPermission ],
					'province-map' => [ 'permission' => $this->crudPermission ],
					'region-map' => [ 'permission' => $this->crudPermission ],
					'city-search' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'province-options' => [ 'post' ],
					'region-options' => [ 'post' ],
					'province-map' => [ 'post' ],
					'region-map' => [ 'post' ],
					'city-search' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'province-options' => [ 'class' => 'cmsgears\core\common\actions\location\data\ProvinceOptions' ],
			'region-options' => [ 'class' => 'cmsgears\core\common\actions\location\data\RegionOptions' ],
			'province-map' => [ 'class' => 'cmsgears\core\common\actions\location\data\ProvinceMap' ],
			'region-map' => [ 'class' => 'cmsgears\core\common\actions\location\data\RegionMap' ],
			'city-search' => [ 'class' => 'cmsgears\core\common\actions\location\data\CitySearch' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// LocationController --------------------

}
