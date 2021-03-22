<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\models\forms\Binder;

use cmsgears\core\common\services\interfaces\mappers\IModelTagService;
use cmsgears\core\common\services\interfaces\resources\ITagService;

/**
 * ModelTagService provide service methods of tag mapper.
 *
 * @since 1.0.0
 */
class ModelTagService extends \cmsgears\core\common\services\base\ModelMapperService implements IModelTagService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelTag';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( ITagService $tagService, $config = [] ) {

		$this->parentService = $tagService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelTagService -----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createFromArray( $parentId, $parentType, $tags ) {

		foreach( $tags as $tagName ) {

			$tagName = trim( $tagName );

			if( empty( $tagName ) ) {

				continue;
			}

			$tag = $this->parentService->getFirstByNameType( $tagName, $parentType );

			if( !isset( $tag ) ) {

				$tag = $this->parentService->createByParams([
					'name' => ucfirst( $tagName ), 'type' => $parentType
				]);
			}

			$modelTag = $this->getFirstByParentModelId( $parentId, $parentType, $tag->id );

			// Create if does not exist
			if( empty( $modelTag ) ) {

				$this->createByParams([
					'modelId' => $tag->id, 'parentId' => $parentId, 'parentType' => $parentType,
					'order' => 0, 'active' => true
				]);
			}
			// Activate if already exist
			else {

				$this->activate( $modelTag );
			}
		}
	}

	public function createFromCsv( $parentId, $parentType, $tags ) {

		$tags = preg_split( "/,/", $tags );

		return $this->createFromArray( $parentId, $parentType, $tags );
	}

	public function createFromJson( $parentId, $parentType, $tags ) {

		return $this->createFromArray( $parentId, $parentType, $tags );
	}

	// Update -------------

	public function bindTags( $parentId, $parentType, $config = [] ) {

		$binderName	= isset( $config[ 'binder' ] ) ? $config[ 'binder' ] : 'Binder';

		$modelBinder = $config[ 'modelBinder' ] ?? null;

		if( empty( $modelBinder ) ) {

			$modelBinder = new Binder();

			$modelBinder->load( Yii::$app->request->post(), $binderName );
		}

		$all	= $modelBinder->all;
		$binded	= $modelBinder->binded;

		$process = [];

		// Check for All
		if( count( $all ) > 0 ) {

			$process = $all;
		}
		// Check for Active
		else {

			$process = $binded;

			$this->disableByParent( $parentId, $parentType );

			$this->createFromArray( $parentId, $parentType, $process );
		}

		return true;
	}

	// Delete -------------

	public function deleteByTagSlug( $parentId, $parentType, $tagSlug, $delete = false ) {

		$tag = $this->parentService->getBySlugType( $tagSlug, $parentType );

		$this->disableByModelId( $parentId, $parentType, $tag->id, $delete = false );
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelTagService -----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
