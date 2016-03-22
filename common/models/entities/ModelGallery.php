<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelGallery Entity
 *
 * @property integer $id
 * @property integer $galleryId
 * @property integer $parentId
 * @property string $parentType
 * @property short $order
 * @property short $active
 */
class ModelGallery extends CmgModel {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'galleryId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id', 'active' ], 'safe' ],
            [ [ 'galleryId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ],
            [ [ 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ]
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
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// ModelGallery ----------------------

	/**
	 * @return Gallery - associated address
	 */
	public function getGallery() {

    	return $this->hasOne( Gallery::className(), [ 'id' => 'galleryId' ] );
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_GALLERY;
	}

	// ModelGallery ----------------------

    // Create -------------

    // Read ---------------

	public static function queryByGalleryId( $parentId, $parentType, $galleryId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND galleryId=:aid', [ ':pid' => $parentId, ':ptype' => $parentType, ':aid' => $galleryId ] );
	}

    public static function queryByParentId( $parentId ) {

        return self::find()->where( 'parentId=:pid',[ ':pid' => $parentId ] );
    }

    // Update -------------

    // Delete -------------

	public static function deleteByGalleryId( $galleryId ) {

		self::deleteAll( 'galleryId=:id', [ ':id' => $galleryId ] );
	}

	public static function deleteByParent( $parentId, $parentType ) {

		self::deleteAll( 'parentId=:pid AND parentType=:ptype', [ ':pid' => $parentId, ':ptype' => $parentType ] );
	}
}

?>