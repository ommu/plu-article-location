<?php
/**
 * Banner Click Details (banner-click-detail)
 * @var $this ClickdetailController
 * @var $model BannerClickDetail
 * @var $form CActiveForm
 * version: 1.3.0
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 January 2017, 21:21 WIB
 * @link https://github.com/ommu/mod-banner
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('id'); ?><br/>
			<?php echo $form->textField($model,'id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('click_id'); ?><br/>
			<?php echo $form->textField($model,'click_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('click_date'); ?><br/>
			<?php echo $form->textField($model,'click_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('click_ip'); ?><br/>
			<?php echo $form->textField($model,'click_ip'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
