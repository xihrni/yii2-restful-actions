<?php

namespace xihrni\yii2\restful\actions;

use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;

/**
 * 状态操作
 *
 * Class StatusAction
 * @package xihrni\yii2\restful\actions
 */
class StatusAction extends Action
{
    /**
     * 运行
     *
     * @param  int $id 主键
     * @return array
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        if (null === Yii::$app->request->post('status')) {
            throw new HttpException(400, Yii::t('app/error', 'Parameter error.'));
        }

        /* @var $model ActiveRecord */
        $model = $this->findModel($id, 0);
        $model->status = Yii::$app->request->post('status') ?? 0;

        if ($model->save(true, ['status'])) {
            return ['status' => $model->status];
        } else {
            throw new HttpException(422, json_encode($model->errors));
        }
    }
}
