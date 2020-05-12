<?php

namespace xihrni\yii2\restful\actions;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * 列表操作
 *
 * Class IndexAction
 * @package xihrni\yii2\restful\actions
 */
class IndexAction extends Action
{
    /**
     * @var callable $query 模型查询类
     *
     * ```php
     * function ($model, $params) {
     *     return $model::find()->where([
     *         'parent_id' => $params['parentId'],
     *         'is_trash' => 0,
     *     ]);
     * }
     * ```
     */
    public $query;

    /**
     * @var array [$sort = ['id' => SORT_DESC]] 排序
     */
    public $sort = ['id' => SORT_DESC];

    /**
     * @var array $pagination 分页
     */
    public $pagination = ['pageSizeLimit' => [1, 50]];


    /**
     * 运行
     *
     * @return ActiveDataProvider
     */
    public function run()
    {
        // TODO: 过滤参数，参考 DataFilter

        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        if ($this->query) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
            // 参数默认值写到表达式中
            $query = call_user_func($this->query, $modelClass, $requestParams);
        } else {
            $query = $modelClass::find()->where(['is_trash' => 0]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => $this->sort,
            ],
            'pagination' => $this->pagination,
        ]);
    }
}
