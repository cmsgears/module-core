<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Gallery;

use cmsgears\core\common\models\traits\MapperTrait;

/**
 * ModelGallery Entity - The mapper to map Gallery Model to specific parent model for given parentId and parentType.
 *
 * @property integer $id
 * @property integer $modelId
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property short $order
 * @property short $active
 */
class ModelGallery extends \cmsgears\core\common\models\base\Mapper {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use MapperTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'modelId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'modelId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'modelId', 'parentId', 'parentType' ], 'unique', 'targetAttribute' => [ 'modelId', 'parentId', 'parentType' ] ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'modelId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GALLERY ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelGallery --------------------------

	/**
	 * @return Gallery - associated address
	 */
	public function getGallery() {

    	return $this->hasOne( Gallery::className(), [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_GALLERY;
	}

	// CMG parent classes --------------------

	// ModelGallery --------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'gallery' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithModel( $config = [] ) {

		$config[ 'relations' ]	= [ 'gallery' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

?>