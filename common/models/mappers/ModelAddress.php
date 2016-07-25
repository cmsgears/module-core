<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Address;

use cmsgears\core\common\models\traits\MapperTrait;

/**
 * ModelAddress Entity - The mapper to map Address Model to specific parent model for given parentId and parentType.
 *
 * @property long $id
 * @property long $modelId
 * @property long $parentId
 * @property string $parentType
 * @property string $type
 * @property short $order
 * @property boolean $active
 */
class ModelAddress extends \cmsgears\core\common\models\base\Mapper {

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
            [ [ 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'modelId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADDRESS ),
            'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADDRESS_TYPE ),
            'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    // ModelAddress --------------------------

    /**
     * @return Address - associated address
     */
    public function getAddress() {

        return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_ADDRESS;
    }

	// CMG parent classes --------------------

	// ModelAddress --------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'address' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithModel( $config = [] ) {

		$config[ 'relations' ]	= [ 'address' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

    /**
     * @param int $parentId
     * @param string $parentType
     * @param string $type
	 * @param boolean $first
     * @return ModelAddress by parent id, parent type and type
     */
    public static function findByType( $parentId, $parentType, $type, $first = false ) {

		if( $first ) {

			return self::queryByParent( $parentId, $parentType )->andWhere( 'type=:type', [ ':type' => $type ] )->one();
		}

        return self::queryByParent( $parentId, $parentType )->andWhere( 'type=:type', [ ':type' => $type ] )->all();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
