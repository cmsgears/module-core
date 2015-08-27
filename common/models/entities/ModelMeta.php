<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelMeta Entity
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $value
 * @property string $type
 * @property string $fieldType
 * @property string $fieldMeta
 */
class ModelMeta extends CmgModel {
 		
	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'value', 'type', 'fieldType', 'fieldMeta' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
            [ [ 'parentId', 'parentType', 'name', 'value' ], 'required' ],
            [ [ 'id', 'type', 'fieldType', 'fieldMeta' ], 'safe' ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validatenameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validatenameUpdate', 'on' => [ 'update' ] ]
        ];

		if( Yii::$app->cmgCore->trimFieldValue ) {

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
			'value' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALUE ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'fieldType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FFIELD_TYPE ),
			'fieldMata' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FFIELD_META )
		];
	}

	// ModelMeta -------------------------

	/**
	 * Validates to ensure that only one meta exist with one name.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->parentId, $this->parentType, $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one meta exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingConfig = self::findByTypeName( $this->parentId, $this->parentType, $this->type, $this->name );

			if( isset( $existingConfig ) && $existingConfig->id != $this->id && 
				$existingConfig->parentId == $this->parentType && strcmp( $existingConfig->parentType, $this->parentType ) == 0 && 
				strcmp( $existingConfig->name, $this->name ) == 0 && $existingConfig->type == $this->type ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_META;
	}

	// ModelMeta -------------------------

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @return array - ModelMeta by type
	 */
	public static function findByType( $parentId, $parentType, $type ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type', 
				[ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type ] )->all();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @return ModelMeta - by name
	 */
	public static function findByName( $parentId, $parentType, $name ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:name', 
				[ ':pid' => $parentId, ':ptype' => $parentType, ':name' => $name ] )->one();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param string $name
	 * @return ModelMeta - by type and name
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
	 * @return boolean - Check whether meta exist by type and name
	 */
	public static function isExistByTypeName( $parentId, $parentType, $type, $name ) {

		$config = self::findByTypeName( $parentId, $parentType, $type, $name );

		return isset( $config );
	}
}

?>