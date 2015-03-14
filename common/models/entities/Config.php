<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\utilities\MessageUtil;

class Config extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->config_id;
	}

	public function getKey() {

		return $this->config_key;
	}

	public function setKey( $key ) {

		$this->config_key = $key;
	}

	public function getValue() {

		return $this->config_value;
	}

	public function setValue( $value ) {

		$this->config_value = $value;
	}

	public function getType() {

		return $this->config_type;
	}

	public function setType( $type ) {

		$this->config_type = $type;
	}

	public function getFieldType() {

		return $this->config_field_type;
	}

	public function setFieldType( $type ) {

		$this->config_field_type = $type;
	}

	public function getFieldData() {

		return $this->config_field_data;
	}

	public function setFieldData( $type ) {

		$this->config_field_data = $type;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'config_key', 'config_value', 'config_type', 'config_field_type' ], 'required' ],
            [ 'config_key', 'alphanumhyphenspace' ],
            [ 'config_key', 'validateKeyCreate', 'on' => [ 'create' ] ],
            [ 'config_key', 'validateKeyUpdate', 'on' => [ 'update' ] ],
            [ 'config_field_data', 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'config_key' => 'Key',
			'config_value' => 'Value',
			'config_type' => 'Type',
			'config_field_type' => 'Field Type',
			'config_field_data' => 'Field Data'
		];
	}

	// Config

    public function validateKeyCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeKey( $this->getType(), $this->getKey() ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateKeyUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingConfig = self::findByTypeKey( $this->getType(), $this->getKey() );

			if( isset( $existingConfig ) && $existingConfig->getId() != $this->getId() && 
				strcmp( $existingConfig->getKey(), $this->getKey() ) == 0 && $existingConfig->getType() == $this->getType() ) {

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

		return self::find()->where( 'config_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByType( $type ) {

		return self::find()->where( 'config_type=:type', [ ':type' => $type ] )->all();
	}

	public static function findByKey( $key ) {

		return self::find()->where( 'config_key=:key', [ ':key' => $key ] )->all();
	}

	public static function findByTypeKey( $type, $key ) {

		return self::find()->where( 'config_key=:key', [ ':key' => $key ] )->andWhere( 'config_type=:type', [ ':type' => $type ] )->one();
	}

	public static function isExistByTypeKey( $type, $key ) {

		$config = self::findByTypeKey( $type, $key );
		
		return isset( $config );
	}
}

?>