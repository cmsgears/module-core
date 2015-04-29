<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Config Entity
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property integer $type
 * @property string $fieldType
 * @property string $fieldMeta
 */
class Config extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'name', 'value', 'type', 'fieldType' ], 'required' ],
            [ [ 'id', 'fieldMeta' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validatenameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validatenameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
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

            if( self::isExistByTypeName( $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one config exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingConfig = self::findByTypeName( $this->type, $this->name );

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

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByType( $type ) {

		return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->all();
	}

	public static function findByTypeName( $type, $name ) {

		return self::find()->where( 'type=:type AND name=:name', [ ':type' => $type, ':name' => $name ] )->one();
	}

	public static function isExistByTypeName( $type, $name ) {

		$config = self::findByTypename( $type, $name );

		return isset( $config );
	}
}

?>