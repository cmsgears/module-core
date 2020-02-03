<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\follower;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IFollower;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Dislike action create/update dislikes and toggle it's value for given parent model.
 *
 * @since 1.0.0
 */
class Dislike extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $direct = true;

	// Protected --------------

	protected $followerService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->followerService = $this->controller->followerService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Dislike -------------------------------

	public function run() {

		$parent	= $this->model;

		if( isset( $parent ) ) {

			$follower	= null;
			$counts		= null;
			$data		= null;

			if( $this->direct ) {

				$follower	= $this->followerService->updateByParams( [ 'parentId' => $parent->id, 'type' => IFollower::TYPE_DISLIKE ] );
				$lcounts	= $this->followerService->getStatusCounts( $parent->id, IFollower::TYPE_LIKE );
				$dlcounts	= $this->followerService->getStatusCounts( $parent->id, IFollower::TYPE_DISLIKE );

				$data = [ 'follower' => $follower->getAttributeArray( [ 'modelId', 'parentId', 'active' ] ), 'likeCounts' => $lcounts, 'dislikeCounts' => $dlcounts ];
			}
			else {

				$follower	= $this->followerService->updateByParams( [ 'parentId' => $parent->id, 'parentType' => $this->parentType, 'type' => IFollower::TYPE_DISLIKE ] );
				$lcounts	= $this->followerService->getStatusCounts( $parent->id, $this->parentType, IFollower::TYPE_LIKE );
				$dlcounts	= $this->followerService->getStatusCounts( $parent->id, $this->parentType, IFollower::TYPE_DISLIKE );

				$data = [ 'follower' => $follower->getAttributeArray( [ 'modelId', 'parentId', 'parentType', 'active' ] ), 'likeCounts' => $lcounts, 'dislikeCounts' => $dlcounts ];
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
