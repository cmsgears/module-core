<?php
namespace cmsgears\modules\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;

use cmsgears\modules\core\common\utilities\MessageUtil;

class LocaleMessage extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->message_id;
	}

	public function getLocaleId() {

		return $this->message_locale;
	}

	public function getLocale() {

		return $this->hasOne( Locale::className(), [ 'locale_id' => 'message_locale' ] );
	}

	public function setLocaleId( $localeId ) {

		$this->message_locale = $localeId;
	}

	public function getKey() {

		return $this->message_key;
	}

	public function setKey( $key ) {

		$this->message_key = $key;
	}

	public function getValue() {

		return $this->message_value;
	}

	public function setValue( $value ) {

		$this->message_value = $value;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'message_locale', 'message_key', 'message_value' ], 'required' ],
            [ 'message_key', 'alphanumhyphenspace' ],
            [ 'message_key', 'validateKeyCreate', 'on' => [ 'create' ] ],
            [ 'message_key', 'validateKeyUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
			'message_locale' => 'Locale',
			'message_key' => 'Key',
			'message_value' => 'Value'
		];
	}

	// Config

    public function validateKeyCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {
			
			$message = self::findByLocaleKey( $this->getLocaleId(), $this->getKey() );

            if( $message ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateKeyUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingMessage = self::findByLocaleKey( $this->getLocaleId(), $this->getKey() );

			if( isset( $existingMessage ) && $existingMessage->getId() != $this->getId() && 
				strcmp( $existingMessage->getKey(), $this->getKey() ) == 0 && $existingMessage->getLocaleId() == $this->getLocaleId() ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {
		
		return CoreTables::TABLE_LOCALE_MESSAGE;
	}

	// LocaleMessage

	public static function findById( $id ) {

		return self::find()->where( [ 'message_id' => $id ] )->one();
	}

	public static function findByLocale( $locale ) {

		return self::find()->where( [ 'message_locale' => $locale->getId() ] )->one();
	}

	public static function findByLocaleId( $localeId ) {

		return self::find()->where( 'message_locale=:id', [ ':id' => $localeId ] )->one();
	}

	public static function findByLocaleKey( $locale, $key ) {

		return self::find()->where( 'message_locale=:id', [ ':id' => $locale->getId() ] )->andWhere( 'message_key=:id', [ ':id' => $key ] )->one();
	}

	public static function findByLocaleIdKey( $localeId, $key ) {

		return self::find()->where( 'message_locale=:id', [ ':id' => $localeId ] )->andWhere( 'message_key=:id', [ ':id' => $key ] )->one();
	}
}

?>