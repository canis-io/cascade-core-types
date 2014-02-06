<?php
namespace cascade\modules\core\TypeFile\models;

use cascade\models\Storage;

/**
 * This is the model class for table "object_file".
 *
 * @property string $id
 * @property string $storage_id
 * @property string $name
 * @property string $created
 * @property string $modified
 *
 * @property Registry $registry
 * @property Storage $storage
 */
class ObjectFile extends \cascade\components\types\ActiveRecord
{
	public $descriptorField = 'name';

	public function getDescriptor()
	{
		$label = $this->name;
		$storage = $this->storage;
		if (!empty($storage)) {
			if (empty($label)) {
				$label = $storage->file_name;
			} else {
				$label .= " ({$storage->file_name})";
			}
		}
		return $label;
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'object_file';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
				'Storage' => [
					'class' => 'cascade\\components\\storageHandlers\\StorageBehavior',
				]
			]);
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['storage_id'], 'required'],
			[['id'], 'string', 'max' => 36],
			[['name'], 'string', 'max' => 255]
		];
	}


	/**
	 * @inheritdoc
	 */
	public function fieldSettings()
	{
		return [
			'name' => [],
			'storage_id' => ['formField' => ['type' => 'file']],
		];
	}


	/**
	 * @inheritdoc
	 */
	public function formSettings($name, $settings = [])
	{
		if (!isset($settings['fields'])) {
			$settings['fields'] = [];
		}
		$settings['fields'][] = ['name'];
		$settings['fields'][] = ['storage_id'];
		// $settings['fields'][] = [];
		return $settings;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'storage_id' => 'File',
			'name' => 'Name',
			'created' => 'Created',
			'modified' => 'Modified',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getRegistry()
	{
		return $this->hasOne(Registry::className(), ['id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getStorage()
	{
		return $this->hasOne(Storage::className(), ['id' => 'storage_id']);
	}
}
