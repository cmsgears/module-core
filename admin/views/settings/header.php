<?php
use yii\helpers\Html; 

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Settings';
?>
<div class="grid-header clearfix">
	<ul class="settings-tab-wrapper">
		<li class="btn core"><?= Html::a( "General", ["/cmgcore/settings/index?type=core"], ['class'=>''] )?></li> 
		<li class="btn email"><?= Html::a( "Email", ["/cmgcore/settings/index?type=email"], ['class'=>''] )?></li>
		<li class="btn admin"><?= Html::a( "Admin", ["/cmgcore/settings/index?type=admin"], ['class'=>''] )?></li>
		<li class="btn frontend"><?= Html::a( "Frontend", ["/cmgcore/settings/index?type=frontend"], ['class'=>''] )?></li>
	</ul>
</div>