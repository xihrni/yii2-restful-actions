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
        if ($this->modelClass === null) {
            throw new InvalidConfigException(get_class($this) . '::$modelClass must be set.');
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
                throw new HttpException(500, Yii::t('app/error', 'The number of keys and values is inconsistent.'));
            }
        } else if ($id !== null) {
            $where = ['id' => $id, 'is_trash' => 0];
            $status && $where['status'] = $status;
            $model = $modelClass::findOne($where);
        }

        if (isset($model)) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app/error', 'Object not found: {id}.', ['id' => $id]));
    }


    /* ----private---- */

    /**
     * 设置翻译
     *
     * @private
     * @return void
     */
    private function _setTranslations()
    {
        $translations = Yii::$app->i18n->translations;

        if (isset($translations['app*']['fileMap']['app/error'])) {
            $messages = $translations['app*']['fileMap']['app/error'];
            $messages = array_merge($messages, [
                'Parameter error.' => '参数错误。',
                'Object not found: {id}.' => '没有找到对象：{id}。',
                'The number of keys and values is inconsistent.' => '键与值的数量不一致。',
            ]);
        } else {
            $translations['app*'] = [
                'class'   => 'yii\i18n\PhpMessageSource',
                'fileMap' => [
                    'app' => 'app.php',
                    'app/error' => 'error.php',
                ],
            ];
        }
    }
}
