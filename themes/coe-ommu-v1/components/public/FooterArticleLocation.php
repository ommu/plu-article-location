<?php

class FooterArticleLocation extends CWidget
{
	public function init() {
	}

	public function run() {
		$this->renderContent();
	}

	protected function renderContent() 
	{
		$module = strtolower(Yii::app()->controller->module->id);
		$controller = strtolower(Yii::app()->controller->id);
		$action = strtolower(Yii::app()->controller->action->id);
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		$currentModule = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
		$currentModuleAction = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		
		//import model
		Yii::import('application.modules.article.model_bpad_coe.ArticleLocations');
		
		$criteria=new CDbCriteria;
		$criteria->with = array(
			'province_relation' => array(
				'alias'=>'province_relation',
				'select'=>'province'
			),
		);
		$criteria->condition = 't.publish = :publish';
		$criteria->params = array(
			':publish'=>1,
		);
		$criteria->order = 'province_relation.province ASC';
			
		$model = ArticleLocations::model()->findAll($criteria);	
		
		$this->render('footer_article_location',array(
			'module' => $module,
			'controller' => $controller,
			'action' => $action,
			'currentAction' => $currentAction,
			'currentModule' => $currentModule,
			'currentModuleAction' => $currentModuleAction,
			'model' => $model,
		));
	}
}
