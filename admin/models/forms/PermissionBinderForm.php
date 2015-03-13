<?php
namespace cmsgears\modules\core\admin\models\forms;

// Yii Imports
use \Yii;
use yii\base\Model;

class PermissionBinderForm extends Model {

	public $roleId;
	public $bindedData;
	
	// yii\base\Model

	public function rules() {

        return [
            [ [ 'roleId', 'bindedData' ], 'required' ],
            [ 'roleId', 'compare', 'compareValue' => 0, 'operator' => '>' ]
        ];
    }
}

?>