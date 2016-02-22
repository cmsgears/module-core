<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelAttribute Entity
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $valueType
 * @property string $value
 */
class ModelAttribute extends Attribute {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {
		
		// model rules
        $rules = [
            [ [ 'parentId', 'parentType', 'name' ], 'required' ],
            [ [ 'id', 'label', 'value' ], 'safe' ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'parentType', 'name', 'type', 'valueType' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'label', 'string', 'min' => 1, 'max' => 150 ],
            [ 'name', 'alphanumu' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'value', 'valueType', 'type' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'label' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
			'value' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALUE ),
			'valueType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALUE_TYPE ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE )
		];
	}

	// ModelAttribute --------------------

	/**
	 * Validates to ensure that only one attribute exist with one name.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->parentId, $this->parentType, $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one attribute exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingConfig = self::findByTypeName( $this->parentId, $this->parentType, $this->type, $this->name );

			if( isset( $existingConfig ) && $existingConfig->id != $this->id && 
				$existingConfig->parentId == $this->parentId && strcmp( $existingConfig->parentType, $this->parentType ) == 0 && 
				strcmp( $existingConfig->name, $this->name ) == 0 && $existingConfig->type == $this->type ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_ATTRIBUTE;
	}

	// ModelAttribute --------------------

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @return array - ModelAttribute by type
	 */
	public static function findByType( $parentId, $parentType, $type ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type', [ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type ] )->all();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @return ModelAttribute - by name
	 */
	public static function findByName( $parentId, $parentType, $name ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:name', [ ':pid' => $parentId, ':ptype' => $parentType, ':name' => $name ] )->one();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param string $name
	 * @return ModelAttribute - by type and name
	 */
	public static function findByTypeName( $parentId, $parentType, $type, $name ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type AND name=:name', 
				[ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param string $name
	 * @return boolean - Check whether attribute exist by type and name
	 */
	public static function isExistByTypeName( $parentId, $parentType, $type, $name ) {

		$config = self::findByTypeName( $parentId, $parentType, $type, $name );

		return isset( $config );
	}
}

?>