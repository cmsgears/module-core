<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\meta;

// Yii Imports
use Yii;
use yii\helpers\HtmlPurifier;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Update Action updates the meta value.
 *
 * @since 1.0.0
 */
class Delete extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $purifyContent = true;

	// Protected --------------

	protected $metaService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->metaService = $this->controller->metaService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Delete --------------------------------

	/**
	 * Delete meta for given meta id, parent slug and parent type.
	 */
	public function run( $cid ) {

		$parent	= $this->model;

		if( isset( $parent ) ) {

			$meta	= $this->metaService->getById( $cid );
			$valid	= $meta->hasAttribute( 'modelId' ) ? $meta->belongsTo( $parent ) : $meta->belongsTo( $parent, $this->modelService->getParentType() );

			if( isset( $meta ) && $valid ) {

				$this->metaService->delete( $meta );

				$value = $this->purifyContent ? HtmlPurifier::process( $meta->value ) : $meta->value;

				$data = [ 'id' => $meta->id, 'name' => $meta->name, 'value' => $value ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
