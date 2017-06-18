<?php
/**
 * ArticleLocations
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 18 October 2016, 02:26 WIB
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
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
 * This is the model class for table "ommu_article_locations".
 *
 * The followings are the available columns in table 'ommu_article_locations':
 * @property integer $location_id
 * @property integer $publish
 * @property integer $province_id
 * @property integer $province_code
 * @property integer $province_desc
 * @property integer $province_photo
 * @property integer $province_header_photo
 * @property string $office_name
 * @property string $office_location
 * @property string $office_place
 * @property integer $office_country
 * @property integer $office_city
 * @property integer $office_district
 * @property integer $office_village
 * @property string $office_zipcode
 * @property string $office_phone
 * @property string $office_fax
 * @property string $office_email
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property OmmuArticleLocationTag[] $ommuArticleLocationTags
 * @property OmmuArticleLocationUser[] $ommuArticleLocationUsers
 */
class ArticleLocations extends CActiveRecord
{
	public $defaultColumns = array();
	public $province_input;
	public $tag_input;
	public $user_input;
	public $old_photo_input;
	public $old_header_photo_input;
	
	// Variable Search
	public $address_search;
	public $email_search;
	public $phone_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleLocations the static model class
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
		return 'ommu_article_locations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('publish, province_code', 'required'),
			array('
				province_input', 'required', 'on'=>'setting'),
			array('office_location, office_place, office_city, office_phone, office_email', 'required', 'on'=>'address'),
			array('location_id, publish, province_id, office_country, office_city', 'numerical', 'integerOnly'=>true),
			array('office_location, office_district, office_village, office_phone, office_fax, office_email,
				tag_input, user_input', 'length', 'max'=>32),
			array('province_code', 'length', 'max'=>16),
			array('office_city, creation_id, modified_id', 'length', 'max'=>11),
			array('office_country, office_zipcode', 'length', 'max'=>5),
			array('province_id, province_desc, province_photo, province_header_photo, office_name, office_location, office_place, office_country, office_city, office_district, office_village, office_zipcode, office_phone, office_fax, office_email,
				province_input, tag_input, user_input, old_photo_input, old_header_photo_input', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('location_id, publish, province_id, province_code, province_desc, province_photo, province_header_photo, office_name, office_location, office_place, office_country, office_city, office_district, office_village, office_zipcode, office_phone, office_fax, office_email, creation_date, creation_id, modified_date, modified_id,
				address_search, email_search, phone_search, province_input, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'country_r' => array(self::BELONGS_TO, 'OmmuZoneCountry', 'office_country'),
			'city_r' => array(self::BELONGS_TO, 'OmmuZoneCity', 'office_city'),
			'district_r' => array(self::BELONGS_TO, 'OmmuZoneDistricts', 'office_district'),	
			'village_r' => array(self::BELONGS_TO, 'OmmuZoneVillage', 'office_village'),	
			'view' => array(self::BELONGS_TO, 'ViewArticleLocations', 'location_id'),
			'tags' => array(self::HAS_MANY, 'ArticleLocationTag', 'location_id'),
			'users' => array(self::HAS_MANY, 'ArticleLocationUser', 'location_id'),
			'province_relation' => array(self::BELONGS_TO, 'OmmuZoneProvince', 'province_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'location_id' => Yii::t('attribute', 'Location'),
			'publish' => Yii::t('attribute', 'Publish'),
			'province_id' => Yii::t('attribute', 'Province'),
			'province_code' => Yii::t('attribute', 'Code'),
			'province_desc' => Yii::t('attribute', 'Description'),
			'province_photo' => Yii::t('attribute', 'Photo'),
			'province_header_photo' => Yii::t('attribute', 'Header Photo'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'office_name' => Yii::t('attribute', 'Office Name'),
			'office_location' => Yii::t('attribute', 'Office Maps Location'),
			'office_place' => Yii::t('attribute', 'Office Address'),
			'office_country' => Yii::t('attribute', 'Office Country'),
			'office_city' => Yii::t('attribute', 'Office City'),
			'office_district' => Yii::t('attribute', 'Office District'),
			'office_village' => Yii::t('attribute', 'Office Village'),
			'office_zipcode' => Yii::t('attribute', 'Office Zipcode'),
			'office_phone' => Yii::t('attribute', 'Office Phone'),
			'office_fax' => Yii::t('attribute', 'Office Fax'),
			'office_email' => Yii::t('attribute', 'Office Email'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'province_input' => Yii::t('attribute', 'Province'),
			'tag_input' => Yii::t('attribute', 'Tag'),
			'user_input' => Yii::t('attribute', 'User'),
			'address_search' => Yii::t('attribute', 'Address'),
			'phone_search' => Yii::t('attribute', 'Phone'),
			'email_search' => Yii::t('attribute', 'Email'),
			'old_photo_input' => Yii::t('attribute', 'Old Photo'),
			'old_header_photo_input' => Yii::t('attribute', 'Old Photo Header'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
		);
		/*
			'Location' => 'Location',
			'Publish' => 'Publish',
			'Province' => 'Province',
			'Creation Date' => 'Creation Date',
			'Creation' => 'Creation',
			'Modified Date' => 'Modified Date',
			'Modified' => 'Modified',
		
		*/
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.location_id',$this->location_id);
		if(isset($_GET['type']) && $_GET['type'] == 'publish')
			$criteria->compare('t.publish',1);
		elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish')
			$criteria->compare('t.publish',0);
		elseif(isset($_GET['type']) && $_GET['type'] == 'trash')
			$criteria->compare('t.publish',2);
		else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		$criteria->compare('t.province_id',$this->province_id);
		$criteria->compare('t.province_code',$this->province_code, true);
		$criteria->compare('t.province_desc',$this->province_desc, true);
		$criteria->compare('t.province_photo',$this->province_photo, true);
		$criteria->compare('t.province_header_photo',$this->province_header_photo, true);
		$criteria->compare('t.office_name',$this->office_name,true);
		$criteria->compare('t.office_location',$this->office_location,true);
		$criteria->compare('t.office_place',$this->office_place,true);
		$criteria->compare('t.office_country',$this->office_country);
		$criteria->compare('t.office_city',$this->office_city);
		$criteria->compare('t.office_district',$this->office_district);
		$criteria->compare('t.office_village',$this->office_village);
		$criteria->compare('t.office_zipcode',$this->office_zipcode,true);
		$criteria->compare('t.office_phone',$this->office_phone,true);
		$criteria->compare('t.office_fax',$this->office_fax,true);
		$criteria->compare('t.office_email',$this->office_email,true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if(isset($_GET['creation']))
			$criteria->compare('t.creation_id',$_GET['creation']);
		else
			$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(isset($_GET['modified']))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		
		// Custom Search
		$criteria->with = array(
			'view' => array(
				'alias'=>'view',
			),
			'province_relation' => array(
				'alias'=>'province_relation',
				'select'=>'province'
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname'
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname'
			),
		);
		$criteria->compare('view.address',strtolower($this->address_search), true);
		$criteria->compare('view.phone',strtolower($this->phone_search), true);
		$criteria->compare('view.email',strtolower($this->email_search), true);
		$criteria->compare('view.tags',strtolower($this->tag_input), true);
		$criteria->compare('view.users',strtolower($this->user_input), true);
		$criteria->compare('province_relation.province',strtolower($this->province_input), true);
		$criteria->compare('creation.displayname',strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search), true);

		if(!isset($_GET['ArticleLocations_sort']))
			$criteria->order = 't.location_id DESC';

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
		} else {
			//$this->defaultColumns[] = 'location_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'province_id';
			$this->defaultColumns[] = 'province_code';
			$this->defaultColumns[] = 'province_desc';
			$this->defaultColumns[] = 'province_photo';
			$this->defaultColumns[] = 'province_header_photo';
			$this->defaultColumns[] = 'office_name';
			$this->defaultColumns[] = 'office_location';
			$this->defaultColumns[] = 'office_place';
			$this->defaultColumns[] = 'office_country';
			$this->defaultColumns[] = 'office_city';
			$this->defaultColumns[] = 'office_district';
			$this->defaultColumns[] = 'office_village';
			$this->defaultColumns[] = 'office_zipcode';
			$this->defaultColumns[] = 'office_phone';
			$this->defaultColumns[] = 'office_fax';
			$this->defaultColumns[] = 'office_email';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
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
				'name' => 'province_input',
				'value' => '$data->province_relation->province',
			);
			$this->defaultColumns[] = array(
				'name' => 'province_code',
				'value' => '$data->province_code',
			);
			$this->defaultColumns[] = array(
				'name' => 'tag_input',
				'value' => 'CHtml::link($data->view->tags, Yii::app()->controller->createUrl("location/tag/manage",array(\'location\'=>$data->location_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'user_input',
				'value' => 'CHtml::link($data->view->users, Yii::app()->controller->createUrl("location/user/manage",array(\'location\'=>$data->location_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'address_search',
				'value' => '$data->view->address == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\') ',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'phone_search',
				'value' => '$data->view->phone == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\') ',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'email_search',
				'value' => '$data->view->email == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\') ',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->location_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Yii::t('phrase', 'Yes'),
						0=>Yii::t('phrase', 'No'),
					),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;			
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		$setting = ArticleSetting::model()->findByPk(1, array(
			'select' => 'media_file_type',
		));
		$media_file_type = unserialize($setting->media_file_type);
		
		if(parent::beforeValidate()) {
			if($this->province_input != '' && ($this->province_id == '' || ($this->province_id != '' && $this->province_id == 0))) {
				$province = OmmuZoneProvince::model()->find(array(
					'select' => 'province_id, province',
					'condition' => 'publish = 1 AND province = :province',
					'params' => array(
						':province' => $this->province_input,
					),
				));
				if($province != null)
					$this->province_id = $province->province_id;
				else
					$this->addError('province_input', Yii::t('phrase', 'Province tidak ditemukan pada database.'));
			}
			
			$province_photo = CUploadedFile::getInstance($this, 'province_photo');
			if($province_photo->name != '') {
				$extension = pathinfo($province_photo->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $media_file_type))
					$this->addError('province_photo', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$province_photo->name,
						'{extensions}'=>Utility::formatFileType($media_file_type, false),
					)));
			}
			
			$province_header_photo = CUploadedFile::getInstance($this, 'province_header_photo');
			if($province_header_photo->name != '') {
				$extension = pathinfo($province_header_photo->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $media_file_type))
					$this->addError('province_header_photo', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$province_header_photo->name,
						'{extensions}'=>Utility::formatFileType($media_file_type, false),
					)));
			}
			
			if($this->isNewRecord) {
				$this->office_country = 72;	
				$this->creation_id = Yii::app()->user->id;	
			} else
				$this->modified_id = Yii::app()->user->id;
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		$action = strtolower(Yii::app()->controller->action->id);
		if(parent::beforeSave()) {
			if(!$this->isNewRecord && in_array($action, array('edit','setting'))) {
				//Update article location photo
				$location_path = "public/article/location";
				
				// Add article directory
				if(!file_exists($location_path)) {
					@mkdir($location_path, 0777, true);

					// Add file in article directory (index.php)
					$newFile = $location_path.'/index.php';
					$FileHandle = fopen($newFile, 'w');
				} else
					@chmod($location_path, 0755, true);
				
				$this->province_photo = CUploadedFile::getInstance($this, 'province_photo');
				if($this->province_photo instanceOf CUploadedFile) {
					$fileName = $this->location_id.'_'.time().'_'.Utility::getUrlTitle($this->province_relation->province).'.'.strtolower($this->province_photo->extensionName);
					if($this->province_photo->saveAs($location_path.'/'.$fileName)) {
						if($this->old_photo_input != '' && file_exists($location_path.'/'.$this->old_photo_input))
							rename($location_path.'/'.$this->old_photo_input, 'public/article/verwijderen/'.$this->location_id.'_'.$this->old_photo_input);
						$this->province_photo = $fileName;
					}
				}					
				if($this->province_photo == '')
					$this->province_photo = $this->old_photo_input;
				
				$this->province_header_photo = CUploadedFile::getInstance($this, 'province_header_photo');
				if($this->province_header_photo instanceOf CUploadedFile) {
					$fileName = $this->location_id.'_'.time().'_'.Utility::getUrlTitle($this->province_relation->province).'.'.strtolower($this->province_header_photo->extensionName);
					if($this->province_header_photo->saveAs($location_path.'/'.$fileName)) {
						if($this->old_header_photo_input != '' && file_exists($location_path.'/'.$this->old_header_photo_input))
							rename($location_path.'/'.$this->old_header_photo_input, 'public/article/verwijderen/'.$this->location_id.'_'.$this->old_header_photo_input);
						$this->province_header_photo = $fileName;
					}
				}					
				if($this->province_header_photo == '')
					$this->province_header_photo = $this->old_header_photo_input;
			}
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();
		
		if($this->isNewRecord) {
			//Update article location photo
			$location_path = "public/article/location";
			
			// Add article directory
			if(!file_exists($location_path)) {
				@mkdir($location_path, 0777, true);

				// Add file in article directory (index.php)
				$newFile = $location_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else
				@chmod($location_path, 0755, true);
			
			$this->province_photo = CUploadedFile::getInstance($this, 'province_photo');
			if($this->province_photo instanceOf CUploadedFile) {
				$fileName = $this->location_id.'_'.time().'_'.Utility::getUrlTitle($this->province_relation->province).'.'.strtolower($this->province_photo->extensionName);
				if($this->province_photo->saveAs($location_path.'/'.$fileName)) {
					self::model()->updateByPk($this->location_id, array('province_photo'=>$fileName));
				}
			}
			
			$this->province_header_photo = CUploadedFile::getInstance($this, 'province_header_photo');
			if($this->province_header_photo instanceOf CUploadedFile) {
				$fileName = $this->location_id.'_'.time().'_'.Utility::getUrlTitle($this->province_relation->province).'.'.strtolower($this->province_header_photo->extensionName);
				if($this->province_header_photo->saveAs($location_path.'/'.$fileName)) {
					self::model()->updateByPk($this->location_id, array('province_header_photo'=>$fileName));
				}
			}
		}
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() {
		parent::afterDelete();
		//delete article location image
		$location_path = "public/article/location";
		if($this->province_photo != '' && file_exists($location_path.'/'.$this->province_photo))
			rename($location_path.'/'.$this->province_photo, 'public/article/verwijderen/'.$this->location_id.'_'.$this->province_photo);
		
		if($this->province_header_photo != '' && file_exists($location_path.'/'.$this->province_header_photo))
			rename($location_path.'/'.$this->province_header_photo, 'public/article/verwijderen/'.$this->location_id.'_'.$this->province_header_photo);
	}

}