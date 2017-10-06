<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\models\traits\MapperTrait;

/**
 * ModelFile Entity - The mapper to map File Model to specific parent model for given parentId and parentType.
 *
 * @property long $id
 * @property long $modelId
 * @property long $parentId
 * @property string $parentType
 * @property string $type
 * @property short $order
 * @property boolean $active
 */
class ModelFile extends \cmsgears\core\common\models\base\Mapper {

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
			// Required, Safe
			[ [ 'modelId', 'parentId', 'parentType' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			// Unique
			[ [ 'modelId', 'parentId', 'parentType' ], 'unique', 'targetAttribute' => [ 'modelId', 'parentId', 'parentType' ] ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			// Other
			[ [ 'modelId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'active' ], 'boolean' ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'modelId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FILE ),
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

	// ModelFile -----------------------------

	/**
	 * @return Gallery - associated files
	 */
	public function getModel() {

		return $this->hasOne( File::className(), [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_FILE;
	}

	// CMG parent classes --------------------

	// ModelFile -----------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'model' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	public static function findByFileTitle( $parentId, $parentType, $fileTitle ) {

		return self::queryByParent( $parentId, $parentType )->andWhere( 'title=:title', [ ':title' => $fileTitle ] )->one();
	}

	public static function findByFileTitleLike( $parentId, $parentType, $title ) {

		return self::queryByParent( $parentId, $parentType )->andFilterWhere( [ 'like', 'title', $title ] )->all();
	}

	public static function findByFileType( $parentId, $parentType, $type ) {

		$fileTable = CoreTables::TABLE_MODEL_FILE;

		return self::queryByParent( $parentId, $parentType )->andFilterWhere( [ 'like', "$fileTable.type", $type ] )->all();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
