<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Category;

use cmsgears\core\common\models\traits\ParentTypeTrait;

/**
 * ModelCategory Entity
 *
 * @property long $id
 * @property long $categoryId
 * @property long $parentId
 * @property string $parentType
 * @property short $order
 * @property boolean $active
 */
class ModelCategory extends \cmsgears\core\common\models\base\Mapper {

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

	use ParentTypeTrait;

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
            [ [ 'categoryId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id'], 'safe' ],
            [ 'parentType', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ],
            [ [ 'categoryId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'categoryId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CATEGORY ),
            'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    // ModelCategory -------------------------

    /**
     * @return Category - associated category
     */
    public function getCategory() {

        return $this->hasOne( Category::className(), [ 'id' => 'categoryId' ] );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_CATEGORY;
    }

	// CMG parent classes --------------------

	// ModelCategory -------------------------

	// Read - Query -----------

	// Read - Find ------------

    public static function findByCategoryId( $parentId, $parentType, $categoryId ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND categoryId=:cid', [ ':pid' => $parentId, ':ptype' => $parentType, ':cid' => $categoryId ] )->one();
    }

    public static function findActiveByParentId( $parentId ) {

        return self::find()->where( 'parentId=:pid AND active=1', [ ':pid' => $parentId ] )->all();
    }

    public static function findActiveByCategoryIdParentType( $categoryId, $parentType ) {

        return self::find()->where( 'categoryId=:cid AND parentType=:ptype AND active=1', [ ':cid' => $categoryId, ':ptype' => $parentType ] )->all();
    }

    public static function findActiveByParent( $parentId, $parentType ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND active=1', [ ':pid' => $parentId, ':ptype' => $parentType ] )->all();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------

    /**
     * Delete all entries related to a category
     */
    public static function deleteByCategoryId( $categoryId ) {

        self::deleteAll( 'categoryId=:id', [ ':id' => $categoryId ] );
    }
}

?>