<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Permission';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-identity';
$this->params['sidebar-child'] 	= 'permission';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Permission</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-permission-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>
		<h4>Assign Roles</h4>
		<?php foreach ( $roles as $role ) { ?>
			<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$role['id']?>" /><?=$role['name']?></span>
		<?php } ?>
		<div class="box-filler"></div>
		<?=Html::a( "Cancel", [ '/cmgcore/permission/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>