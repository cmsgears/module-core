<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;

class JsonModel extends \yii\base\Model {

	public function __construct( $jsonData = false ) {

        if( $jsonData ) {

			if( is_object( $jsonData ) ) {

				$this->copyFromObject( $jsonData );
			}
			else if( is_array( $jsonData ) ) {
				
				$this->copyFromArray( $jsonData );
			}
			else {

				$this->setData( json_decode( $jsonData, true ) );
			}
		}
    }

    private function setData( $data ) {

        foreach( $data as $key => $value ) {

            $this->{ $key } = $value;
        }
    }

    private function copyFromObject( $object ) {

		$attributes	= get_object_vars( $object );

        foreach ( $attributes as $key => $value ) {

            $this->{ $key } = $value;
        }
    }

    private function copyFromArray( $arr ) {

        foreach ( $arr as $key => $value ) {

            $this->{ $key } = $value;
        }
    }
}

?>