<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Tag;
use cmsgears\core\common\models\mappers\ModelTag;

use cmsgears\core\common\services\interfaces\resources\ITagService;
use cmsgears\core\common\services\interfaces\mappers\IModelTagService;

use cmsgears\core\common\services\traits\MapperTrait;

/**
 * The class ModelTagService is base class to perform database activities for ModelTag Entity.
 */
class ModelTagService extends \cmsgears\core\common\services\base\EntityService implements IModelTagService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelTag';

	public static $modelTable	= CoreTables::TABLE_MODEL_TAG;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $tagService;

	// Traits ------------------------------------------------------

	use MapperTrait;

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

	public function createFromCsv( $parentId, $parentType, $tags ) {

		$tags	= preg_split( "/,/", $tags );

		foreach ( $tags as $tagName ) {

			if( empty( $tagName ) ) {

				continue;
			}

			$tagName	= trim( $tagName );
			$tag		= Tag::findByNameType( $tagName, $parentType );

			if( !isset( $tag ) ) {

				$tag			= new Tag();
				$tag->siteId	= Yii::$app->core->siteId;
				$tag->name		= ucfirst( $tagName );
				$tag->type		= $parentType;
				$tag			= $this->tagService->create( $tag );
			}

			$modelTag	= $this->getByModelId( $parentId, $parentType, $tag->id );

			// Create if does not exist
			if( !isset( $modelTag ) ) {

				$this->createByParams( [ 'modelId' => $tag->id, 'parentId' => $parentId, 'parentType' => $parentType, 'order' => 0, 'active' => true ] );
			}
			// Activate if already exist
			else {

				self::activate( $modelTag );
			}
		}
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'order', 'active' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function deleteByTagSlug( $parentId, $parentType, $tagSlug, $delete = false ) {

		$tag	= $this->tagService->getBySlugType( $tagSlug, $parentType );

		$this->disableByModelId( $parentId, $parentType, $tag->id, $delete = false );

		return true;
	}

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
