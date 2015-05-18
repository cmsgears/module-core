<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Config Entity
 *
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $value
 * @property integer $type
 * @property string $fieldType
 * @property string $fieldMeta
 */
class ModelConfig extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'parentId', 'parentType', 'name', 'value', 'type', 'fieldType' ], 'required' ],
            [ [ 'fieldMeta' ], 'safe' ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'max' => 100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validatenameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validatenameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
			'parentId' => 'Parent',
			'parentType' => 'Parent Type',
			'name' => 'Name',
			'value' => 'Value',
			'type' => 'Type',
			'fieldType' => 'Field Type',
			'fieldMata' => 'Field Meta Json'
		];
	}

	// Config ----------------------------

	/**
	 * Validates to ensure that only one config exist with one name.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->parentId, $this->parentType, $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one config exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingConfig = self::findByTypeName( $this->parentId, $this->parentType, $this->type, $this->name );

			if( isset( $existingConfig ) && $existingConfig->id != $this->id && 
				strcmp( $existingConfig->name, $this->name ) == 0 && $existingConfig->type == $this->type ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_CONFIG;
	}

	// Config ----------------------------

	public static function findByType( $parentId, $parentType, $type ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type', 
				[ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type ] )->all();
	}

	public static function findByName( $parentId, $parentType, $name ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:name', 
				[ ':pid' => $parentId, ':ptype' => $parentType, ':name' => $name ] )->all();
	}

	public static function findByTypeName( $parentId, $parentType, $type, $name ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type AND name=:name', 
				[ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type, ':name' => $name ] )->one();
	}

	public static function isExistByTypeName( $parentId, $parentType, $type, $name ) {

		$config = self::findByTypeName( $parentId, $parentType, $type, $name );

		return isset( $config );
	}
}

?>