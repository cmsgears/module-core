<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * ModelHierarchy Entity
 *
 * @property integer $id
 * @property integer $parentId
 * @property integer $childId
 * @property integer $rootId
 * @property string $parentType
 * @property string $lValue
 * @property short $rValue
 */
class ModelHierarchy extends \cmsgears\core\common\models\base\CmgModel {

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
            [ [ 'rootId' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'parentType' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ [ 'parentId', 'childId', 'rootId', 'lValue', 'rValue' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE )
		];
	}

	// ModelHierarchy --------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_HIERARCHY;
	}

	// ModelHierarchy --------------------

    // Create -------------

    // Read ---------------

    public static function findRoot( $rootId, $parentType ) {

        return self::find()->where( 'rootId=:rid AND parentId=NULL parentType=:type', [ ':rid' => $rootId, ':type' => $parentType ] )->one();
    }

    // Update -------------

    // Delete -------------
}

?>