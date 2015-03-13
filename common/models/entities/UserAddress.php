<?php
namespace cmsgears\modules\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class UserAddress extends ActiveRecord {

	const TYPE_NATIVE 	= 0; 
	const TYPE_MAILING 	= 1;

	// Instance methods --------------------------------------------------

	// db columns

	public function getUserId() {

		return $this->user_id;
	}

	public function setUserId( $id ) {

		$this->user_id = $id;
	}

	public function getAddressId() {

		return $this->user_address;
	}

	public function setAddressId( $id ) {

		$this->user_address = $id;
	}

	public function getAddressType() {

		return $this->user_address_type;
	}

	public function setAddressType( $type ) {

		$this->user_address_type = $type;
	}

	// Static methods --------------------------------------------------

	public static function tableName() {

		return CoreTables::TABLE_USER_ADDRESS;
	}

	public static function findById( $id ) {

		return UserAddress::findOne( $id );
	}

	// Address

	public static function findAllByUser( $user ) {

		return UserAddress::find()->where( [ 'user_id' => $user->getId() ] )->all();
	}

	public static function findNativeByUser( $user ) {
		
		$userAddress 	= UserAddress::find()->where( [ 'user_id' => $user->getId(), 'user_address_type' => self::TYPE_NATIVE ] )->one();
		$address 		= null;

		if( isset( $userAddress ) ) {
			
			$address	= Address::findById( $userAddress->getAddressId() );
		}

		return $address;
	}

	public static function findMailingByUser( $user ) {
		
		$userAddress 	= UserAddress::find()->where( [ 'user_id' => $user->getId(), 'user_address_type' => self::TYPE_MAILING ] )->one();
		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= Address::findById( $userAddress->getAddressId() );
		}

		return $address;
	}
}

?>