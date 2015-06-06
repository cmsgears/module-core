<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Create User";
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Create User</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-user-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'email' ) ?>
    	<?= $form->field( $model, 'username' ) ?>
		<?= $form->field( $siteMember, 'roleId' )->dropDownList( $roles )  ?>
		<?= $form->field( $model, 'gender' )->dropDownList( $genders )  ?>
		<?= $form->field( $model, 'firstName' ) ?>
		<?= $form->field( $model, 'lastName' ) ?>
		<?= $form->field( $model, 'phone' ) ?>
		<?= $form->field( $model, 'newsletter' )->checkbox() ?>
		
		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />
		
		<?php ActiveForm::end(); ?>
	</div>	
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", 4 );
</script>