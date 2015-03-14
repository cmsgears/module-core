<?php
namespace cmsgears\core\admin\models\forms;

// Yii Imports
use \Yii;
use yii\base\Model;

class RoleBinderForm extends Model {

	public $permissionId;
	public $bindedData;
	
	// yii\base\Model

	public function rules() {

        return [
            [ [ 'permissionId', 'bindedData' ], 'required' ],
            [ 'permissionId', 'compare', 'compareValue' => 0, 'operator' => '>' ]
        ];
    }
}

?>