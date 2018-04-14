<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\base;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IMeta;
use cmsgears\core\common\models\interfaces\resources\IData;

use cmsgears\core\common\models\traits\base\MetaTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * Meta represents meta data of a model. It's the base class for Models having
 * separate table to store meta data.
 *
 * Example: Site and SiteMeta where Site is the entity model and SiteMeta stores attributes
 * and meta data of sites.
 *
 * A model can have multiple meta mapped to it, but only one meta with the same name of
 * a particular type is allowed for a model. We can have value type hints using $valueType.
 *
 * @property integer $id
 * @property integer $modelId
 * @property string $name
 * @property string $label
 * @property string $type
 * @property boolean $active
 * @property string $valueType
 * @property string $value
 * @property string $data
 *
 * @since 1.0.0
 */
abstract class Meta extends Resource implements IData, IMeta {

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
			[ [ 'modelId', 'name' ], 'required' ],
			[ [ 'id', 'value', 'data' ], 'safe' ],
			// Unique
			[ [ 'modelId', 'type', 'name' ], 'unique', 'targetAttribute' => [ 'modelId', 'type', 'name' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ [ 'type', 'valueType' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'label', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ 'active', 'boolean' ],
			[ 'modelId', 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'type', 'valueType', 'value' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'modelId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'label' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'valueType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE_TYPE ),
			'value' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Meta ----------------------------------

	/**
	 * Returns the parent model using one-to-one(hasOne) relationship.
	 *
	 * @return ActiveRecord The parent model to which this meta belongs.
	 */
	abstract public function getParent();

	/**
	 * Checks whether the meta belong to given parent model.
	 *
	 * @return boolean
	 */
	public function belongsTo( $model ) {

		return $this->modelId == $model->id;
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

	// CMG parent classes --------------------

	// Meta ----------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'parent' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the meta with parent model.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with parent model.
	 */
	public static function queryWithParent( $config = [] ) {

		$config[ 'relations' ] = [ 'parent' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the meta by parent id.
	 *
	 * @param integer $modelId Parent Id.
	 * @return \yii\db\ActiveQuery to query by parent id.
	 */
	public static function queryByModelId( $modelId ) {

		return static::find()->where( 'modelId=:pid', [ ':pid' => $modelId ] );
	}

	/**
	 * Return query to find the meta by parent id and meta name.
	 *
	 * @param integer $modelId Parent Id.
	 * @param string $name
	 * @return \yii\db\ActiveQuery to query by parent id and meta name.
	 */
	public static function queryByName( $modelId, $name ) {

		return static::find()->where( 'modelId=:pid AND name=:name', [ ':pid' => $modelId, ':name' => $name ] );
	}

	/**
	 * Return query to find the meta by parent id and meta type.
	 *
	 * @param integer $modelId Parent Id.
	 * @param string $type
	 * @return \yii\db\ActiveQuery to query by parent id and meta type.
	 */
	public static function queryByType( $modelId, $type ) {

		return static::find()->where( 'modelId=:pid AND type=:type', [ ':pid' => $modelId, ':type' => $type ] );
	}

	/**
	 * Return query to find the meta by parent id, meta name and meta type.
	 *
	 * @param integer $modelId Parent Id.
	 * @param string $name
	 * @param string $type
	 * @return \yii\db\ActiveQuery to query by parent id, meta name and meta type.
	 */
	public static function queryByNameType( $modelId, $name, $type ) {

		return static::find()->where( 'modelId=:pid AND name=:name AND type=:type', [ ':pid' => $modelId, ':name' => $name, ':type' => $type ] );
	}

	// Read - Find ------------

	/**
	 * Return meta models by parent id.
	 *
	 * @param integer $modelId Parent Id.
	 * @return ModelMeta[] by parent id.
	 */
	public static function findByModelId( $modelId ) {

		return self::queryByModelId( $modelId )->all();
	}

	/**
	 * Return meta models by parent id and meta name.
	 *
	 * @param integer $modelId Parent Id.
	 * @param string $name
	 * @return ModelMeta[] by parent id and meta name.
	 */
	public static function findByName( $modelId, $name ) {

		$query	= self::queryByName( $modelId, $name );

		return $query->all();
	}

	/**
	 * Return first meta model by parent id and meta name.
	 *
	 * @param integer $modelId Parent Id.
	 * @param string $name
	 * @return ModelMeta|array|null by parent id and meta name.
	 */
	public static function findFirstByName( $modelId, $name ) {

		$query	= self::queryByName( $modelId, $name );

		return $query->one();
	}

	/**
	 * Return meta models by parent id and meta type.
	 *
	 * @param integer $modelId Parent Id.
	 * @param string $type
	 * @return ModelMeta[] by parent id and meta type.
	 */
	public static function findByType( $modelId, $type ) {

		return self::queryByType( $modelId, $type )->all();
	}

	/**
	 * Return meta model by parent id, meta name and meta type.
	 *
	 * @param integer $modelId Parent Id.
	 * @param string $name
	 * @param string $type
	 * @return ModelMeta|array|null by parent id, meta name and meta type.
	 */
	public static function findByNameType( $modelId, $name, $type ) {

		return self::queryByNameType( $modelId, $name, $type )->one();
	}

	/**
	 * Check whether meta exist by parent id, meta name and meta type.
	 *
	 * @param integer $modelId Parent Id.
	 * @param string $name
	 * @param string $type
	 * @return boolean
	 */
	public static function isExistByNameType( $modelId, $name, $type ) {

		$meta = self::findByNameType( $modelId, $type, $name );

		return isset( $meta );
	}

	// Create -----------------

	// Update -----------------

	/**
	 * Update the meta value for given parent id, name, type.
	 *
	 * @param integer $modelId Parent Id.
	 * @param string $name
	 * @param string $type
	 * @param type $value
	 * @return int|false either 1 or false if meta not found or validation fails.
	 */
	public static function updateByNameType( $modelId, $name, $type, $value ) {

		$meta = self::findByNameType( $modelId, $name, $type );

		if( isset( $meta ) ) {

			$meta->value = $value;

			return $meta->update();
		}

		return false;
	}

	// Delete -----------------

	/**
	 * Delete all meta related to a model.
	 *
	 * @return int the number of rows deleted.
	 */
	public static function deleteByModelId( $modelId ) {

		return self::deleteAll( 'modelId=:id', [ ':id' => $modelId ] );
	}

}
