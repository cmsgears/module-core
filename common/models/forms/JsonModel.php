<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;

class JsonModel extends \yii\base\Model {

	public function __construct( $jsonData = false ) {

        if( $jsonData ) {

			$this->setData( json_decode( $jsonData, true ) );
		}
    }

    private function setData( $data ) {

        foreach( $data as $key => $value ) {

            $this->{ $key } = $value;
        }
    }
}

?>