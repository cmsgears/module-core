<?php
namespace cmsgears\core\common\behaviors;

use \Yii;
use yii\db\BaseActiveRecord;

class AuthorBehavior extends \yii\behaviors\AttributeBehavior {

	public $createdByAttribute = 'createdBy';

	public $updatedByAttribute = 'modifiedBy';

	public $value;

	public function init() {

		parent::init();

		if( empty( $this->attributes ) ) {

			$this->attributes = [
				BaseActiveRecord::EVENT_BEFORE_INSERT => [ $this->createdByAttribute, $this->updatedByAttribute ],
				BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedByAttribute,
			];
		}
	}

	protected function getValue( $event ) {

		// User current user in case createdBy is not set
		if( !isset( $this->owner->createdBy ) && Yii::$app->user->getIdentity() != null ) {

			return Yii::$app->user->identity->id;
		}

		if( isset( $this->owner->createdBy ) ) {

			return $this->owner->createdBy;
		}
	}

	public function touch( $attribute ) {

		$owner = $this->owner;

		$owner->updateAttributes( array_fill_keys( ( array ) $attribute, $this->getValue(null)));
	}
}
