<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii2mod\editable\EditableColumn;

/* @var $this yii\web\View */
/* @var $searchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('systemparam', 'Manage Params');
$this->params['breadcrumbs'][] = $this->title;
$attributesMap = Yii::$app->get('systemparams')->modelMap['systemParam']['class']::attributesMap();
?>
<div class="system-param-model-index">
    <h1><?= Html::encode($this->title); ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            $attributesMap['fieldParamKey'],
            $attributesMap['fieldDescription'],
            [
                'class' => EditableColumn::className(),
                'attribute' => $attributesMap['fieldParamValue'],
                'url' => ['update'],
                'editableOptions' => function ($model) {
                    switch ($model->validator) {
                        case 'boolean':
                            return [
                                'type' => 'select',
                                'source' => [1 => 'Active', 0 => 'Unactive'],
                            ];
                        default:
                            return [];
                    }
                },
            ],
        ],
    ]); ?>
</div>
