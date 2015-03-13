<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Role';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Role</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-role-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'role_name' ) ?>
    	<?= $form->field( $model, 'role_desc' )->textarea() ?>
    	<?= $form->field( $model, 'role_home' ) ?>
		<h4>Assign Permissions</h4>
		<?php foreach ( $permissions as $permission ) { ?>
			<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$permission['id']?>" /><?=$permission['name']?></span>
		<?php } ?>
		<div class="box-filler"></div>
		<?=Html::a( "Cancel", [ '/cmgcore/role/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", 2 );
</script>