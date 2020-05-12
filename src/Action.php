<?php

namespace xihrni\yii2\restful\actions;

use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * 基础操作类
 *
 * Class Action
 * @package xihrni\yii2\restful\actions
 */
class Action extends \yii\base\Action
{
    /**
     * @var string $modelClass 模型类文件
     */
    public $modelClass;

    /**
     * @var callable $findModel 查询模型方法
     *
     * ```php
     * function ($id, $status, $action) {
     *     // ...
     * }
     * ```
     */
    public $findModel;


    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        // 注册翻译
        $this->_registerTranslations();

        if ($this->modelClass === null) {
            throw new InvalidConfigException(static::t('error', '{param} must be set.', [
                'param' => get_class($this) . '::$modelClass'
            ]));
        }
    }

    /**
     * 查询模型
     *
     * @param  int|string $id           主键，例：1 或 1,2
     * @param  int        [$status = 1] 状态，0=>不查询状态，1=>查询状态为1的数据
     * @return object
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function findModel($id, $status = 1)
    {
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey(); // 主键

        if (count($keys) > 1) { // 多主键
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $where = array_combine($keys, $values);
                $where['is_trash'] = 0;
                $where['status'] = $status;
                $model = $modelClass::findOne($where);
            } else {
                throw new HttpException(500, static::t('error', 'The number of keys and values is inconsistent.'));
            }
        } else if ($id !== null) {
            $where = ['id' => $id, 'is_trash' => 0];
            $status && $where['status'] = $status;
            $model = $modelClass::findOne($where);
        }

        if (isset($model)) {
            return $model;
        }

        throw new NotFoundHttpException(static::t('error', 'Object not found: {id}.', ['id' => $id]));
    }


    /* ----private---- */

    /**
     * 将消息转换为指定的语言
     *
     * @param  string $category 消息分类
     * @param  string $message  消息内容
     * @param  array  $params   将用于替换消息中相应占位符的参数
     * @param  string $language 语言代码（例如 en-US、en ）
     * @return string
     */
    protected static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('xihrni/yii2/restful/actions/' . $category, $message, $params, $language);
    }

    /**
     * 注册翻译
     *
     * @private
     * @return void
     */
    private function _registerTranslations()
    {
        Yii::$app->i18n->translations['xihrni/yii2/restful/actions/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/xihrni/yii2-restful-actions/src/messages',
            'fileMap' => [
                'xihrni/yii2/restful/actions/error' => 'error.php',
            ],
        ];
    }
}
