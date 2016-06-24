<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Gallery;

/**
 * ModelGallery Entity
 *
 * @property integer $id
 * @property integer $galleryId
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property short $order
 * @property short $active
 */
class ModelGallery extends \cmsgears\core\common\models\base\ParentMapper {

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
            [ [ 'galleryId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ],
            [ [ 'galleryId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'galleryId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_GALLERY ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
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

    	return $this->hasOne( Gallery::className(), [ 'id' => 'galleryId' ] );
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

	public static function queryByGalleryId( $parentId, $parentType, $galleryId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND galleryId=:aid', [ ':pid' => $parentId, ':ptype' => $parentType, ':aid' => $galleryId ] );
	}

    public static function queryByParentId( $parentId ) {

        return self::find()->where( 'parentId=:pid',[ ':pid' => $parentId ] );
    }

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	public static function deleteByGalleryId( $galleryId ) {

		self::deleteAll( 'galleryId=:id', [ ':id' => $galleryId ] );
	}
}

?>