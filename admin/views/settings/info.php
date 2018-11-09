<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\widgets\aform\AjaxFormWidget;
?>
<div class="row frm-split-40-60">
	<?php if( isset( $fieldsMap ) && count( $fieldsMap ) > 0 ) { ?>

	<?= AjaxFormWidget::widget([
		'options' => [ 'class' => 'form', 'cmt-keep' => 1 ],
		'slug' => "config-$type", 'type' => CoreGlobal::TYPE_SYSTEM,
		'labels' => true, 'form' => $form, 'formName' => "setting$type",
		'ajaxUrl' => "core/settings/update?type=$type",
		'cmtApp' => 'core', 'cmtController' => 'settings', 'cmtAction' => 'update'
	])?>

	<?php } else { ?>
	<p>No settings found.</p>
	<?php } ?>
</div>
