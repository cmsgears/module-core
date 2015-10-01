<?php
use \Yii;
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Profile";

$gender		= 'Not Assigned';
$phone		= 'Not Assigned';
$newsletter	= 'Not Subscribed';

if( $model->getGenderStr() != null ) {
	
	$gender	= $model->getGenderStr();
}

if( $model->phone != null ) {
	
	$phone	= $model->phone;
}

if( $model->newsletter ==1 ) {
	
	$newsletter	= 'Subscribed';
}

$this->params['sidebar-parent'] = 'sidebar-profile';
$this->params['sidebar-child'] 	= 'profile';

?>
<section class="box-simple">
	
	<div class="content box-cud">

		<div class="clear"></div>
    	<h4>My Profile</h4>
    	
    	<div class="clear row right">
    		<a class="fa fa-edit right icon-action icon-action-edit btn-edit-profile"></a>
    	</div>
    	<div class="frm-split frm-view-profile">
    		 	<div>
		    		<label> Email </label>
		    		<input type="text" readonly="" value="<?= $model->email ?>">
	    		</div>
	    	 	<div>
		    		<label> Username </label>
		    		<input type="text"  readonly="" value=" <?= $model->username ?>" >
	    		</div>
	    		<div>
		    		<label> Username </label>
		    		<input type="text" readonly=""  value=" <?= $model->username ?>" >
	    		</div>
	    		<div>
		    		<label> Firstname </label>
		    		<input type="text"  readonly="" value=" <?= $model->firstName ?>" >
	    		</div>
	    		<div>
		    		<label> Lastname </label>
		    		<input type="text"  readonly="" value=" <?= $model->lastName ?>" >
	    		</div>
	    		<div>
		    		<label> Gender </label>
		    		<input type="text"  readonly="" value=" <?= $gender ?>" >
	    		</div>
	    		<div>
		    		<label> Phone </label>
		    		<input type="text" readonly=""  value=" <?= $phone ?>" >
	    	 	</div>
	    	 	
	    	 	<div>
		    		<label> Newsletter </label>
		    		<input type="text" readonly=""  value=" <?= $newsletter ?>" >
	    	 	</div>
    	</div>	
    	
    	
    	<div class="frm-edit hidden">
			<?php $form = ActiveForm::begin( ['id' => 'frm-user-profile', 'options' => ['class' => 'frm-split' ] ] );?>
	
			<?= $form->field( $model, 'email' )->textInput( [ 'readonly' => true ] ) ?>
			<?= $form->field( $model, 'username' ) ?>
			<?= $form->field( $model, 'firstName' ) ?>
			<?= $form->field( $model, 'lastName' ) ?>
			<?= $form->field( $model, 'genderId' )->dropDownList( $genders )  ?>
			<?= $form->field( $model, 'phone' ) ?>
			<?= $form->field( $model, 'newsletter' )->checkbox() ?>
	
			<div class="clear"></div>
			<div class="align-middle"><input type="submit" class="btn-fancy medium" value="Update" /></div>
	
			<?php ActiveForm::end(); ?>
		</div>	
	</div>
</section>