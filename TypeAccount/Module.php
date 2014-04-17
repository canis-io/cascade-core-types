<?php
/**
 * @link http://www.infinitecascade.com/
 * @copyright Copyright (c) 2014 Infinite Cascade
 * @license http://www.infinitecascade.com/license/
 */

namespace cascade\modules\core\TypeAccount;

use Yii;

use cascade\components\types\Relationship;
use infinite\helpers\ArrayHelper;

/**
 * Module [@doctodo write class description for Module]
 *
 * @author Jacob Morrison <email@ofjacob.com>
**/
class Module extends \cascade\components\types\Module
{
	protected $_title = 'Account';
	public $icon = 'fa fa-building-o';
	public $uniparental = false;
	public $hasDashboard = true;
	public $priority = 105;
	public $primaryAsParent = true;
	public $parentSearchWeight = .2;

	public $widgetNamespace = 'cascade\\modules\\core\\TypeAccount\\widgets';
	public $modelNamespace = 'cascade\\modules\\core\\TypeAccount\\models';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		Yii::$app->registerMigrationAlias('@cascade/modules/core/TypeAccount/migrations');
	}


	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
			'Authority' => [
				'class' => 'cascade\\components\\security\\AuthorityBehavior'
			]
		]);
	}

	public function setup() {
		$results = [true];
		if (!empty($this->primaryModel)) {
			$primaryAccount = Yii::$app->gk->primaryAccount;
			if ($primaryAccount) {
				$results[] = $this->objectTypeModel->setRole(['system_id' => 'editor'], $primaryAccount, true);
			}
			$publicGroup = Yii::$app->gk->publicGroup;
			if ($publicGroup) {
				$results[] = $this->objectTypeModel->setRole(['system_id' => 'browser'], $publicGroup, true);
			}
		}
		return min($results);
	}

	public function determineOwner($object)
	{
        return false;
	}
	
	/**
	 * @inheritdoc
	 */
	public function getRequestors($accessingObject, $firstLevel = true)
	{
		if (!$firstLevel) {
			$parentAccounts = $accessingObject->parents($this->primaryModel, [], ['disableAccessCheck' => true]);
			if (!empty($parentAccounts)) {
				return ArrayHelper::getColumn($parentAccounts, 'id', false);
			}
		}
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function widgets()
	{
		$widgets = parent::widgets();
		$widgets['ParentAccountBrowse']['section'] = Yii::$app->collectors['sections']->getOne('_side');
		return $widgets;
	}

	
	/**
	 * @inheritdoc
	 */
	public function parents()
	{
		return [
			'Account' => [],
		];
	}

	
	/**
	 * @inheritdoc
	 */
	public function children()
	{
		return [
			'Account' => [],
			'Individual' => [
				'temporal' => true
			],
			'PhoneNumber' => [],
			'PostalAddress' => [],
			'WebAddress' => [],
		];
	}

	
	/**
	 * @inheritdoc
	 */
	public function taxonomies()
	{
		return [];
	}
}
?>