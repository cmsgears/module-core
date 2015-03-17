<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\utilities\MessageUtil;

class Config extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'key', 'value', 'type', 'fieldType' ], 'required' ],
            [ 'key', 'alphanumhyphenspace' ],
            [ 'key', 'validateKeyCreate', 'on' => [ 'create' ] ],
            [ 'key', 'validateKeyUpdate', 'on' => [ 'update' ] ],
            [ 'fieldMeta', 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'key' => 'Key',
			'value' => 'Value',
			'type' => 'Type',
			'fieldType' => 'Field Type',
			'fieldMata' => 'Field Json Meta'
		];
	}

	// Config

    public function validateKeyCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeKey( $this->type, $this->key ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateKeyUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingConfig = self::findByTypeKey( $this->type, $this->key );

			if( isset( $existingConfig ) && $existingConfig->id != $this->id && 
				strcmp( $existingConfig->key, $this->key ) == 0 && $existingConfig->type == $this->type ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_CONFIG;
	}

	// Config

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByType( $type ) {

		return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
	}

	public static function findByKey( $key ) {

		return self::find()->where( 'key=:key', [ ':key' => $key ] )->all();
	}

	public static function findByTypeKey( $type, $key ) {

		return self::find()->where( 'key=:key', [ ':key' => $key ] )->andWhere( 'type=:type', [ ':type' => $type ] )->one();
	}

	public static function isExistByTypeKey( $type, $key ) {
		
		$config = self::findByTypeKey( $type, $key );

		return isset( $config );
	}
}

?>