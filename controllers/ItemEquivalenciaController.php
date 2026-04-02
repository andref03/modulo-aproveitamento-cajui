<?php

namespace app\controllers;

use Yii;
use app\models\ItemEquivalencia;
use app\models\ItemEquivalenciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemEquivalenciaController implements the CRUD actions for ItemEquivalencia model.
 */
class ItemEquivalenciaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ItemEquivalencia models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ItemEquivalenciaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ItemEquivalencia model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ItemEquivalencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($solicitacao_id = null)
    {
        $model = new ItemEquivalencia();

        if ($solicitacao_id !== null) {
            $model->solicitacao_id = $solicitacao_id;

            $solicitacao = \app\models\SolicitacaoAproveitamento::findOne($solicitacao_id);

            if ($solicitacao && !$solicitacao->podeEditar()) {
                Yii::$app->session->setFlash('error', 'Não é possível adicionar itens a esta solicitação.');
                return $this->redirect(['solicitacao-aproveitamento/view', 'id' => $solicitacao_id]);
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Item adicionado com sucesso.');
            return $this->redirect(['solicitacao-aproveitamento/update', 'id' => $model->solicitacao_id]);
        }

        $model->loadDefaultValues();

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ItemEquivalencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!$model->solicitacao->podeEditar() && $model->solicitacao->status !== 'EM_ANALISE') {
            Yii::$app->session->setFlash('error', 'Este item não pode mais ser editado.');
            return $this->redirect(['solicitacao-aproveitamento/view', 'id' => $model->solicitacao_id]);
        }

        if ($this->request->isPost) {
            $model->load($this->request->post());

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Item atualizado com sucesso.');
                return $this->redirect(['solicitacao-aproveitamento/update', 'id' => $model->solicitacao_id]);
            } else {
                dd($model->errors);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ItemEquivalencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $solicitacaoId = $model->solicitacao_id;

        if (!$model->solicitacao->podeEditar()) {
            Yii::$app->session->setFlash('error', 'Este item não pode mais ser removido.');
            return $this->redirect(['solicitacao-aproveitamento/view', 'id' => $solicitacaoId]);
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Item removido com sucesso.');

        return $this->redirect(['solicitacao-aproveitamento/update', 'id' => $solicitacaoId]);
    }

    /**
     * Finds the ItemEquivalencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ItemEquivalencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ItemEquivalencia::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
