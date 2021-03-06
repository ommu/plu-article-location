<?php
/**
 * Articles
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/ommu-article-location
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_articles".
 *
 * The followings are the available columns in table 'ommu_articles':
 * @property string $article_id
 * @property integer $publish
 * @property integer $cat_id
 * @property integer $article_type
 * @property string $title
 * @property string $body
 * @property string $quote 
 * @property string $media_file
 * @property string $published_date
 * @property integer $headline
 * @property integer $comment_code
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 * @property string $headline_date
 *
 * The followings are the available model relations:
 * @property ArticleComment[] $ArticleComments
 * @property ArticleMedia[] $ArticleMedias
 * @property ArticleCategory $cat
 */

class Articles extends CActiveRecord
{
	use UtilityTrait;
	use GridViewTrait;

	public $defaultColumns = array();
	public $media_input;
	public $old_media_input;
	public $video_input;
	public $keyword_input;
	public $old_media_file_input;
	
	// Variable Search
	public $creation_search;
	public $modified_search;
	public $media_search;
	public $view_search;
	public $like_search;
	public $downlaod_search;
	public $tag_search;

	/**
	 * Behaviors for this model
	 */
	public function behaviors() 
	{
		return array(
			'sluggable' => array(
				'class'=>'ext.yii-sluggable.SluggableBehavior',
				'columns' => array('title'),
				'unique' => true,
				'update' => true,
			),
		);
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Articles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_articles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id, article_type, body, published_date', 'required'),
			array('title', 'required', 'on'=>'formStandard'),
			array('title,
				video_input', 'required', 'on'=>'formVideo'),
			array('publish, cat_id, headline, comment_code, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('creation_id, modified_id', 'length', 'max'=>11),
			array('
				video_input', 'length', 'max'=>32),
			array('title', 'length', 'max'=>128),
			//array('media_input', 'file', 'types' => 'jpg, jpeg, png, gif', 'allowEmpty' => true),
			//array('file', 'file', 'types' => 'mp3, mp4,
			//	pdf, doc, docx, ppt, pptx, xls, xlsx, opt', 'maxSize'=>7097152, 'allowEmpty' => true),
			array('article_type, title, body, quote, published_date, creation_date, modified_date, 
				media_input, old_media_input, video_input, keyword_input, old_media_file_input', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('article_id, publish, cat_id, article_type, title, body, quote, media_file, published_date, headline, comment_code, creation_date, creation_id, modified_date, modified_id, headline_date,
				creation_search, modified_search, media_search, view_search, like_search, downlaod_search, tag_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'view' => array(self::BELONGS_TO, 'ViewArticlesLocations', 'article_id'),
			'cat' => array(self::BELONGS_TO, 'ArticleCategory', 'cat_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'tag' => array(self::HAS_ONE, 'ArticleTag', 'article_id'),
			'medias' => array(self::HAS_MANY, 'ArticleMedia', 'article_id', 'on'=>'medias.publish=1'),
			'tags' => array(self::HAS_MANY, 'ArticleTag', 'article_id'),
			'collection' => array(self::HAS_ONE, 'ArticleCollections', 'article_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'article_id' => Yii::t('attribute', 'Article'),
			'publish' => Yii::t('attribute', 'Publish'),
			'cat_id' => Yii::t('attribute', 'Category'),
			'article_type' => Yii::t('attribute', 'Article Type'),
			'title' => Yii::t('attribute', 'Title'),
			'body' => Yii::t('attribute', 'Article'),
			'quote' => Yii::t('attribute', 'Quote'),
			'media_file' => Yii::t('attribute', 'File (Download)'),
			'published_date' => Yii::t('attribute', 'Published Date'),
			'headline' => Yii::t('attribute', 'Headline'),
			'comment_code' => Yii::t('attribute', 'Comment'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'headline_date' => Yii::t('attribute', 'Headline Date'),
			'media_input' => Yii::t('attribute', 'Media').' (Photo)',
			'old_media_input' => Yii::t('attribute', 'Old Media').' (Photo)',
			'video_input' => Yii::t('attribute', 'Video'),
			'keyword_input' => Yii::t('attribute', 'Keyword'),
			'old_media_file_input' => Yii::t('attribute', 'Old File (Download)'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'media_search' => Yii::t('attribute', 'Photos'),
			'view_search' => Yii::t('attribute', 'Views'),
			'like_search' => Yii::t('attribute', 'Likes'),
			'downlaod_search' => Yii::t('attribute', 'Downloads'),
			'tag_search' => Yii::t('attribute', 'Tags'),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		
		// Custom Search
		$criteria->with = array(
			'view' => array(
				'alias' => 'view',
			),
			'creation' => array(
				'alias' => 'creation',
				'select' => 'displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
			'tags' => array(
				'alias' => 'tags',
				'select' => 'tag_id',
				'together'=>true,
			),
		);

		$criteria->compare('t.article_id', $this->article_id);
		if(Yii::app()->getRequest()->getParam('type') == 'publish')
			$criteria->compare('t.publish', 1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish')
			$criteria->compare('t.publish', 0);
		elseif(Yii::app()->getRequest()->getParam('type') == 'trash')
			$criteria->compare('t.publish', 2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}

		if(Yii::app()->getRequest()->getParam('category')) {
			$category = ArticleCategory::model()->findByPk(Yii::app()->getRequest()->getParam('category'));
			if($category->parent == 0) {
				$parent = Yii::app()->getRequest()->getParam('category');
				$categoryFind = ArticleCategory::model()->findAll(array(
					'condition' => 'parent = :parent',
					'params' => array(
						':parent' => $parent,
					),
				));
				$items = array();
				$items[] = Yii::app()->getRequest()->getParam('category');
				if($categoryFind != null) {
					foreach($categoryFind as $key => $val) {
						$items[] = $val->cat_id;
					}
				}
				$criteria->addInCondition('t.cat_id',$items);
				$criteria->compare('t.cat_id', $this->cat_id);
				
			} else
				$criteria->compare('t.cat_id', Yii::app()->getRequest()->getParam('category'));
		} else
			$criteria->compare('t.cat_id', $this->cat_id);
		$criteria->compare('t.article_type', strtolower($this->article_type), true);
		$criteria->compare('t.title', strtolower($this->title), true);
		$criteria->compare('t.body', strtolower($this->body), true);
		$criteria->compare('t.quote', strtolower($this->quote), true);
		$criteria->compare('t.media_file', strtolower($this->media_file), true);
		if($this->published_date != null && !in_array($this->published_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.published_date)', date('Y-m-d', strtotime($this->published_date)));
		$criteria->compare('t.headline', $this->headline);
		$criteria->compare('t.comment_code', $this->comment_code);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		
		if(Yii::app()->user->level == 2) {
			$location = ArticleLocationUser::model()->find(array(
				'select' => 'location_id, user_id',
				'condition' => 'user_id = :user',
				'params' => array(
					':user' => Yii::app()->user->id,
				),
			));
			if($location != null) {
				$tags = $location->location->tags;
				if(!empty($tags)) {
					$items = array();
					foreach($tags as $key => $val)
						$items[] = $val->tag_id;
					$criteria->addInCondition('tags.tag_id', $items);
				}
			} else
				$criteria->compare('t.creation_id',Yii::app()->user->id);
			
		} else
			$criteria->compare('t.creation_id', $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		if(Yii::app()->getRequest()->getParam('modified'))
			$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified'));
		else
			$criteria->compare('t.modified_id', $this->modified_id);
		if($this->headline_date != null && !in_array($this->headline_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.headline_date)', date('Y-m-d', strtotime($this->headline_date)));
		
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.medias', $this->media_search);
		$criteria->compare('view.views', $this->view_search);
		$criteria->compare('view.likes', $this->like_search);
		$criteria->compare('view.downloads', $this->downlaod_search);
		$criteria->compare('view.tags', $this->tag_search);

		if(!Yii::app()->getRequest()->getParam('Articles_sort'))
			$criteria->order = 't.article_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}

	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		}else {
			//$this->defaultColumns[] = 'article_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'article_type';
			$this->defaultColumns[] = 'title';
			$this->defaultColumns[] = 'body';
			$this->defaultColumns[] = 'quote';
			$this->defaultColumns[] = 'media_file';
			$this->defaultColumns[] = 'published_date';
			$this->defaultColumns[] = 'headline';
			$this->defaultColumns[] = 'comment_code';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
			$this->defaultColumns[] = 'headline_date';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() 
	{
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select' => 'gridview_column, headline',
		));
		$gridview_column = unserialize($setting->gridview_column);		
		if(empty($gridview_column))
			$gridview_column = array();
		
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'title',
				'value' => '$data->title',
			);
			$category = ArticleCategory::model()->findByPk(Yii::app()->getRequest()->getParam('category'));
			if(!Yii::app()->getRequest()->getParam('category') || (Yii::app()->getRequest()->getParam('category') && $category->parent == 0)) {
				if($category->parent == 0)
					$parent = Yii::app()->getRequest()->getParam('category');
				else
					$parent = null;
				$this->defaultColumns[] = array(
					'name' => 'cat_id',
					'value' => 'Phrase::trans($data->cat->name)',
					'filter'=> ArticleCategory::getCategory(null, $parent),
					'type' => 'raw',
				);
			}
			if(in_array('creation_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'creation_search',
					'value' => '$data->creation->displayname',
				);
			}
			if(in_array('creation_date', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'creation_date',
					'value' => 'Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterDatepicker($this, 'creation_date'),
				);
			}
			if(in_array('published_date', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'published_date',
					'value' => 'Yii::app()->dateFormatter->formatDateTime($data->published_date, \'medium\', false)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=> $this->filterDatepicker($this, 'published_date'),
				);
			}
			if(in_array('media_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'media_search',
					'value' => 'CHtml::link($data->view->medias ? $data->view->medias : 0, Yii::app()->controller->createUrl("o/media/manage", array(\'article\'=>$data->article_id)))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);
			}
			if(in_array('view_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'view_search',
					'value' => 'CHtml::link($data->view->views ? $data->view->views : 0, Yii::app()->controller->createUrl("o/views/manage", array(\'article\'=>$data->article_id)))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);
			}
			if(in_array('like_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'like_search',
					'value' => 'CHtml::link($data->view->likes ? $data->view->likes : 0, Yii::app()->controller->createUrl("o/like/manage", array(\'article\'=>$data->article_id)))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);
			}
			if(in_array('downlaod_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'downlaod_search',
					'value' => 'CHtml::link($data->view->downloads ? $data->view->downloads : 0, Yii::app()->controller->createUrl("o/download/manage", array(\'article\'=>$data->article_id)))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);
			}
			if(in_array('tag_search', $gridview_column)) {
				$this->defaultColumns[] = array(
					'name' => 'tag_search',
					'value' => 'CHtml::link($data->view->tags ? $data->view->tags : 0, Yii::app()->controller->createUrl("o/tag/manage", array(\'article\'=>$data->article_id)))',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);
			}
			if($setting->headline == 1) {
				$this->defaultColumns[] = array(
					'name' => 'headline',
					'value' => 'in_array($data->cat_id, ArticleSetting::getHeadlineCategory()) ? ($data->headline == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Utility::getPublish(Yii::app()->controller->createUrl("headline", array("id"=>$data->article_id)), $data->headline, 9)) : \'-\'',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
					'type' => 'raw',
				);
			}
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->article_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * Articles get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
 			if(count(explode(',', $column)) == 1)
 				return $model->$column;
 			else
 				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * Articles get information
	 */
	public static function getHeadline()
	{
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select' => 'headline_limit, headline_category',
		));
		$headline_category = unserialize($setting->headline_category);
		if(empty($headline_category))
			$headline_category = array();
					
		$criteria=new CDbCriteria;
		$criteria->compare('publish', 1);
		$criteria->addInCondition('cat_id', $headline_category);
		$criteria->compare('headline', 1);
		$criteria->order = 'headline_date DESC';
		
		$model = self::model()->findAll($criteria);
		
		$headline = array();
		if(!empty($model)) {
			$i=0;
			foreach($model as $key => $val) {
				$i++;
				if($i <= $setting->headline_limit)
					$headline[] = $val->article_id;
			}
		}
		
		return $headline;
	}

	/**
	 * Albums get information
	 */
	public function searchIndexing($index)
	{
		Yii::import('application.modules.article.models.*');
		
		$criteria=new CDbCriteria;
		$criteria->compare('publish', 1);
		$criteria->compare('date(published_date) <', date('Y-m-d H:i:s'));
		$criteria->order = 'article_id DESC';
		//$criteria->limit = 10;
		$model = Articles::model()->findAll($criteria);
		foreach($model as $key => $item) {
			$medias = $item->medias;
			if(!empty($medias)) {
				$media = $item->view->media_cover ? $item->view->media_cover : $medias[0]->media;
				$image = Yii::app()->request->baseUrl.'/public/article/'.$item->article_id.'/'.$media;
			} else 
				$image = '';
			$url = Yii::app()->createUrl('article/site/view', array('id'=>$item->article_id,'slug'=>$this->urlTitle($item->title)));
				
			$doc = new Zend_Search_Lucene_Document();
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('id', CHtml::encode($item->article_id), 'utf-8')); 
			$doc->addField(Zend_Search_Lucene_Field::Keyword('category', CHtml::encode(Phrase::trans($item->cat->name)), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('media', CHtml::encode($image), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('title', CHtml::encode($item->title), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('body', CHtml::encode(Utility::hardDecode(Utility::softDecode($item->body))), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('url', CHtml::encode(Utility::getProtocol().'://'.Yii::app()->request->serverName.$url), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('date', CHtml::encode($item->published_date), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('creation', CHtml::encode($item->creation->displayname), 'utf-8'));
			$index->addDocument($doc);			
		}
		
		return true;
	}

	/**
	 * User get information
	 */
	public static function getShareUrl($id, $slug=null)
	{
		if($slug && $slug != '-')
			return Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->controller->createUrl('site/view', array('id'=>$id, 'slug'=>$this->urlTitle($slug)));
		else
			return Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->controller->createUrl('site/view', array('id'=>$id));
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select' => 'media_file_type, upload_file_type',
		));
		$media_file_type = unserialize($setting->media_file_type);
		if(empty($media_file_type))
			$media_file_type = array();
		$upload_file_type = unserialize($setting->upload_file_type);
		if(empty($upload_file_type))
			$upload_file_type = array();
		
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;
			else
				$this->modified_id = Yii::app()->user->id;
			
			if($this->headline == 1 && $this->publish == 0)
				$this->addError('publish', Yii::t('phrase', 'Publish cannot be blank.'));
			
			$media_input = CUploadedFile::getInstance($this, 'media_input');
			if($media_input != null && $this->article_type == 'standard') {
				$extension = pathinfo($media_input->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $media_file_type))
					$this->addError('media_input', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$media_input->name,
						'{extensions}'=>Utility::formatFileType($media_file_type, false),
					)));
			}
			
			$media_file = CUploadedFile::getInstance($this, 'media_file');
			if($media_file != null) {
				$extension = pathinfo($media_file->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $upload_file_type))
					$this->addError('media_file', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$media_file->name,
						'{extensions}'=>Utility::formatFileType($upload_file_type, false),
					)));
			}
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		if(parent::beforeSave()) {
			if(!$this->isNewRecord && $this->article_type != 'quote') {
				$article_path = "public/article/".$this->article_id;
				// Add directory
				if(!file_exists($article_path)) {
					@mkdir($article_path, 0755, true);

					// Add file in directory (index.php)
					$newFile = $article_path.'/index.php';
					$FileHandle = fopen($newFile, 'w');
				} else
					@chmod($article_path, 0755, true);
				
				$this->media_file = CUploadedFile::getInstance($this, 'media_file');
				if($this->media_file != null) {
					if($this->media_file instanceOf CUploadedFile) {
						$fileName = time().'_'.$this->urlTitle($this->title).'.'.strtolower($this->media_file->extensionName);
						if($this->media_file->saveAs($article_path.'/'.$fileName)) {
							if($this->old_media_file_input != '' && file_exists($article_path.'/'.$this->old_media_file_input))
								rename($article_path.'/'.$this->old_media_file_input, 'public/article/verwijderen/'.$this->article_id.'_'.$this->old_media_file_input);
							$this->media_file = $fileName;
						}
					}
				} else {
					if($this->media_file == '')
						$this->media_file = $this->old_media_file_input;
				}
			}
			
			$this->published_date = date('Y-m-d', strtotime($this->published_date));
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() 
	{
		parent::afterSave();
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select' => 'headline, media_limit, media_resize, media_resize_size',
		));
		$media_resize_size = unserialize($setting->media_resize_size);
		
		$article_path = "public/article/".$this->article_id;
		if($this->article_type != 'quote') {
			// Add directory
			if(!file_exists($article_path)) {
				@mkdir($article_path, 0755, true);

				// Add file in directory (index.php)
				$newFile = $article_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else
				@chmod($article_path, 0755, true);
		}

		if($this->isNewRecord) {
			//input keyword
			$location = ArticleLocationUser::model()->find(array(
				'select' => 'location_id, user_id',
				'condition' => 'user_id = :user',
				'params' => array(
					':user' => Yii::app()->user->id,
				),
			));
			if($location != null) {
				$tags = $location->location->tags;
				if(!empty($tags)) {
					foreach($tags as $key => $val) {
						$tag = new ArticleTag;
						$tag->article_id = $this->article_id;
						$tag->tag_id = $val->tag_id;
						$tag->save();
					}
				}
			}
			
			if(trim($this->keyword_input) != '') {
				$keyword_input = Utility::formatFileType($this->keyword_input);
				if(!empty($keyword_input)) {
					foreach($keyword_input as $key => $val) {
						$subject = new ArticleTag;
						$subject->article_id = $this->article_id;
						$subject->tag_id = 0;
						$subject->tag_input = $val;
						$subject->save();
					}
				}
			}
			
			//upload media file (download)
			$this->media_file = CUploadedFile::getInstance($this, 'media_file');
			if($this->media_file != null) {
				if($this->media_file instanceOf CUploadedFile) {
					$fileName = time().'_'.$this->urlTitle($this->title).'.'.strtolower($this->media_file->extensionName);
					if($this->media_file->saveAs($article_path.'/'.$fileName))
						Articles::model()->updateByPk($this->article_id, array('media_file'=>$fileName));
				}
			}
		}

		if($this->article_type == 'standard') {
			$this->media_input = CUploadedFile::getInstance($this, 'media_input');
			if($this->media_input != null && ($this->isNewRecord || (!$this->isNewRecord && ($setting->media_limit == 1 || ($setting->media_limit != 1 && $this->cat->single_photo == 1))))) {
				if($this->media_input instanceOf CUploadedFile) {
					$fileName = time().'_'.$this->urlTitle($this->title).'.'.strtolower($this->media_input->extensionName);
					if($this->media_input->saveAs($article_path.'/'.$fileName)) {
						if($this->isNewRecord || (!$this->isNewRecord && $this->medias == null)) {
							$images = new ArticleMedia;
							$images->cover = 1;
							$images->article_id = $this->article_id;
							$images->media = $fileName;
							$images->save();
						} else {
							if($this->old_media_input != '' && file_exists($article_path.'/'.$this->old_media_input))
								rename($article_path.'/'.$this->old_media_input, 'public/article/verwijderen/'.$this->article_id.'_'.$this->old_media_input);
							$medias = $this->medias;
							$media_id = $this->view->media_id ? $this->view->media_id : $medias[0]->media_id;
							if(ArticleMedia::model()->updateByPk($media_id, array('media'=>$fileName))) {
								if($setting->media_resize == 1)
									ArticleMedia::resizePhoto($article_path.'/'.$fileName, $media_resize_size);
							}
						}
					}
				}
			}

		} else if($this->article_type == 'video') {
			$medias = $this->medias;
			if($this->isNewRecord || (!$this->isNewRecord && $medias == null)) {
				$video = new ArticleMedia;
				$video->cover = 1;
				$video->article_id = $this->article_id;
				$video->media = $this->video_input;
				$video->save();
			} else
				ArticleMedia::model()->updateByPk($medias[0]->media_id, array('media'=>$this->video_input));
		}
		
		// Reset headline
		if($setting->headline == 1 && $this->headline == 1) {
			$headline = self::getHeadline();
			
			$criteria=new CDbCriteria;
			$criteria->addNotInCondition('article_id', $headline);
			self::model()->updateAll(array('headline'=>0), $criteria);
		}
	}

	/**
	 * Before delete attributes
	 */
	protected function beforeDelete() {
		if(parent::beforeDelete()) {
			$article_path = "public/article/".$this->article_id;
			
			//delete media photos
			$medias = $this->medias;
			if(!empty($medias)) {
				foreach($medias as $val) {
					if($val->media != '' && file_exists($article_path.'/'.$val->media))
						rename($article_path.'/'.$val->media, 'public/article/verwijderen/'.$val->article_id.'_'.$val->media);					
				}
			}
			//delete media file
			if($this->media_file != '' && file_exists($article_path.'/'.$this->media_file))
				rename($article_path.'/'.$this->media_file, 'public/article/verwijderen/'.$this->article_id.'_'.$this->media_file);
		}
		return true;
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() {
		parent::afterDelete();
		//delete article image
		$article_path = "public/article/".$this->article_id;
		Utility::deleteFolder($article_path);		
	}

}