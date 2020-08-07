<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\apix\sites;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * MemberController provides actions specific to site members.
 *
 * @since 1.0.0
 */
class MemberController extends \cmsgears\core\admin\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $userService;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Services
		$this->modelService	= Yii::$app->factory->get( 'siteMemberService' );

		$this->userService	= Yii::$app->factory->get( 'userService' );
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
					'bulk' => [ 'permission' => $this->crudPermission ],
					'generic' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'bulk' => [ 'post' ],
					'generic' => [ 'post' ],
					'delete' => [ 'post' ],
					'auto-search' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk', 'admin' => true ],
			'generic' => [ 'class' => 'cmsgears\core\common\actions\grid\Generic' ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ]
		];
	}

	public function actionAutoSearch() {

		$name	= Yii::$app->request->post( 'name' );
		$siteId	= Yii::$app->request->get( 'sid' );

		$memberTable	= $this->modelService->getModelTable();
		$userTable		= $this->userService->getModelTable();

		$userClass	= $this->userService->getModelClass();
		$query		= $userClass::find();

		$query->andWhere( "NOT EXISTS (SELECT * FROM {$memberTable} WHERE {$memberTable}.siteId=:sid AND {$memberTable}.userId={$userTable}.id)", [ ':sid' => $siteId ] );

		$data = $this->userService->getIdNameListByUsername( $name, [ 'query' => $query ] );

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CityController ------------------------

}
