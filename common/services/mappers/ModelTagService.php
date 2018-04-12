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

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelTagService provide service methods of tag mapper.
 *
 * @since 1.0.0
 */
class ModelTagService extends ModelMapperService implements IModelTagService {

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

	private $tagService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( ITagService $tagService, $config = [] ) {

		$this->tagService	= $tagService;

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

		foreach ( $tags as $tagName ) {

			$tagName = trim( $tagName );

			if( empty( $tagName ) ) {

				continue;
			}

			$tag = $this->tagService->getFirstByNameType( $tagName, $parentType );

			if( !isset( $tag ) ) {

				$tag = $this->tagService->createByParams( [ 'siteId' => Yii::$app->core->siteId, 'name' => ucfirst( $tagName ), 'type' => $parentType ] );
			}

			$modelTag = $this->getByModelId( $parentId, $parentType, $tag->id );

			// Create if does not exist
			if( !isset( $modelTag ) ) {

				$this->createByParams( [ 'modelId' => $tag->id, 'parentId' => $parentId, 'parentType' => $parentType, 'order' => 0, 'active' => true ] );
			}
			// Activate if already exist
			else {

				$this->activate( $modelTag );
			}
		}
	}

	public function createFromCsv( $parentId, $parentType, $tags ) {

		$tags	= preg_split( "/,/", $tags );

		return $this->createFromArray( $parentId, $parentType, $tags );
	}

	public function createFromJson( $parentId, $parentType, $tags ) {

		return $this->createFromArray( $parentId, $parentType, $tags );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'order', 'active' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function bindTags( $parentId, $parentType, $config = [] ) {

		$binderName	= isset( $config[ 'binder' ] ) ? $config[ 'binder' ] : 'Binder';
		$binder		= new Binder();

		$binder->load( Yii::$app->request->post(), $binderName );

		$all		= $binder->all;
		$binded		= $binder->binded;
		$process	= [];

		// Check for All
		if( count( $all ) > 0 ) {

			$process = $all;

			// TODO: Handle the situation where all check is required. Refer bindCategories of ModelCategoryService
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

		$tag = $this->tagService->getBySlugType( $tagSlug, $parentType );

		$this->disableByModelId( $parentId, $parentType, $tag->id, $delete = false );

		return true;
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
