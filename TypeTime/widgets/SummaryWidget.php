<?php
/**
 * @link http://www.infinitecascade.com/
 *
 * @copyright Copyright (c) 2014 Infinite Cascade
 * @license http://www.infinitecascade.com/license/
 */

namespace cascade\modules\core\TypeTime\widgets;

use infinite\helpers\ArrayHelper;
use Yii;

/**
 * SummaryWidget [@doctodo write class description for SummaryWidget].
 *
 * @author Jacob Morrison <email@ofjacob.com>
 */
class SummaryWidget extends \cascade\components\web\widgets\base\WidgetArea
{
    /**
     * @inheritdoc
     */
    public $defaultDecoratorClass = 'cascade\components\web\widgets\decorator\BlankDecorator';
    /**
     * @var __var__stats_type__ __var__stats_description__
     */
    protected $_stats;
    /**
     * @inheritdoc
     */
    public $location = 'right';

    /**
     * Get grid cell settings.
     *
     * @return __return_getGridCellSettings_type__ __return_getGridCellSettings_description__
     */
    public function getGridCellSettings()
    {
        return [
            'columns' => 5,
            'maxColumns' => 6,
            'htmlOptions' => ['class' => 'no-left-padding'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getIsReady()
    {
        return !empty($this->stats['total']);
    }

    /**
     * Get stats.
     *
     * @return __return_getStats_type__ __return_getStats_description__
     */
    public function getStats()
    {
        if (is_null($this->_stats)) {
            $this->_stats = $this->module->getStats(Yii::$app->request->object);
        }

        return $this->_stats;
    }

    /**
     * @inheritdoc
     */
    public function generateContent()
    {
        if (!empty($this->stats['total'])) {
            return $this->render('summary', ['stats' => $this->stats]);
        }

        return false;
    }

    /**
     * Get module.
     *
     * @return __return_getModule_type__ __return_getModule_description__
     */
    public function getModule()
    {
        $method = ArrayHelper::getValue($this->parentWidget->settings, 'queryRole', 'all');
        $relationship = ArrayHelper::getValue($this->parentWidget->settings, 'relationship', false);
        if ($method === 'children') {
            return $relationship->child;
        } elseif ($method === 'parents') {
            return $relationship->parent;
        }

        return false;
    }
}
