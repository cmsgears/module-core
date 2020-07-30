<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IMeta;
use cmsgears\core\common\models\interfaces\resources\IData;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\base\MetaTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * ModelMeta represents meta data of a model. It's the shared table to store the
 * meta data.
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $parentType
 * @property string $icon
 * @property string $name
 * @property string $label
 * @property string $type
 * @property boolean $active
 * @property integer $order
 * @property string $valueType
 * @property string $value
 * @property string $data
 *
 * @since 1.0.0
 */
class ModelMeta extends \cmsgears\core\common\models\base\ModelResource implements IData, IMeta {

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

	use DataTrait;
	use MetaTrait;

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

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'parentId', 'parentType', 'name' ], 'required' ],
			[ [ 'id', 'value' ], 'safe' ],
			// Unique
			[ 'name', 'unique', 'targetAttribute' => [ 'parentId', 'parentType', 'type', 'name' ], 'comboNotUnique' => 'Attribute with the same name and type already exist.' ],
			// Text Limit
			[ [ 'parentType', 'type', 'valueType' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'label', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ 'active', 'boolean' ],
			[ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'parentId', 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'label', 'valueType', 'type', 'value' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'label' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'valueType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE_TYPE ),
			'value' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( empty( $this->order ) || $this->order <= 0 ) {

				$this->order = 0;
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelMeta -----------------------------

	/**
	 * Check whether the meta belongs to given parent model.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $parent
	 * @param string $parentType
	 * @return boolean
	 */
	public function belongsTo( $parent, $parentType ) {

		return $this->parentId == $parent->id && $this->parentType == $parentType;
	}

	/**
	 * Returns string representation of active flag.
	 *
	 * @return boolean
	 */
	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_META );
	}

	// CMG parent classes --------------------

	// ModelMeta -----------------------------

	// Read - Query -----------

	/**
	 * Return query to find the model meta by given parent id, parent type and name.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by parent id, parent type and name.
	 */
	public static function queryByName( $parentId, $parentType, $name, $config = [] ) {

		$query = static::queryByParent( $parentId, $parentType, $config );

		$query->andWhere( 'name=:name', [ ':name' => $name ] );

		return $query;
	}

	/**
	 * Return query to find the model meta by given parent id, parent type and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by parent id, parent type and name.
	 */
	public static function queryByType( $parentId, $parentType, $type, $config = [] ) {

		$query = static::queryByParent( $parentId, $parentType, $config );

		$query->andWhere( 'type=:type', [ ':type' => $type ] );

		return $query;
	}

	/**
	 * Return query to find the model meta by given parent id, parent type, name and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param string $type
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query by parent id, parent type and name.
	 */
	public static function queryByNameType( $parentId, $parentType, $name, $type, $config = [] ) {

		$query = static::queryByParent( $parentId, $parentType, $config );

		$query->andWhere( 'name=:name AND type=:type', [ ':name' => $name, ':type' => $type ] );

		return $query;
	}

	// Read - Find ------------

	/**
	 * Find and return the meta associated with parent for given name.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param array $config
	 * @return ModelMeta[]
	 */
	public static function findByName( $parentId, $parentType, $name, $config = [] ) {

		return self::queryByName( $parentId, $parentType, $name, $config )->all();
	}

	/**
	 * Find and return the meta associated with parent for given type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param array $config
	 * @return ModelMeta[]
	 */
	public static function findByType( $parentId, $parentType, $type, $config = [] ) {

		return self::queryByType( $parentId, $parentType, $type, $config )->all();
	}

	/**
	 * Find and return the meta associated with parent for given name and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param string $type
	 * @param array $config
	 * @return ModelMeta
	 */
	public static function findByNameType( $parentId, $parentType, $name, $type, $config = [] ) {

		return self::queryByNameType( $parentId, $parentType, $name, $type, $config )->one();
	}

	/**
	 * Check whether meta exist for parent using given name and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param string $type
	 * @param array $config
	 * @return boolean
	 */
	public static function isExistByNameType( $parentId, $parentType, $name, $type, $config = [] ) {

		$meta = self::queryByNameType( $parentId, $parentType, $name, $type, $config )->one();

		return isset( $meta );
	}

	// Create -----------------

	// Update -----------------

	/**
	 * Update the meta value for given parent id, parent type, name and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param string $type
	 * @param type $value
	 * @return int|false either 1 or false if meta not found or validation fails.
	 */
	public static function updateByNameType( $parentId, $parentType, $name, $type, $value, $config = [] ) {

		$meta = self::findByNameType( $parentId, $parentType, $name, $type, $value, $config );

		if( isset( $meta ) ) {

			$meta->value = $value;

			return $meta->update();
		}

		return false;
	}

	// Delete -----------------

}
