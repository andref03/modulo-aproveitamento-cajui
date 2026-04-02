<?php

namespace app\controllers;
use app\models\ItemEquivalencia;
use Yii;
use app\models\SolicitacaoAproveitamento;
use app\models\SolicitacaoAproveitamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SolicitacaoAproveitamentoController implements the CRUD actions for SolicitacaoAproveitamento model.
 */
class SolicitacaoAproveitamentoController extends Controller
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
                        'enviar' => ['POST'],
                        'finalizar' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all SolicitacaoAproveitamento models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SolicitacaoAproveitamentoSearch();

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => \app\models\SolicitacaoAproveitamento::find()
                ->with(['estudante', 'coordenador'])
                ->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SolicitacaoAproveitamento model.
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
     * Creates a new SolicitacaoAproveitamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SolicitacaoAproveitamento();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Solicitação criada com sucesso.');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $model->loadDefaultValues();

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SolicitacaoAproveitamento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!$model->podeEditar()) {
            Yii::$app->session->setFlash('error', 'Esta solicitação não pode mais ser editada.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Solicitação atualizada com sucesso.');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SolicitacaoAproveitamento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SolicitacaoAproveitamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return SolicitacaoAproveitamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SolicitacaoAproveitamento::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionEnviar($id)
    {
        $model = $this->findModel($id);

        if (!$model->podeEnviar()) {
            Yii::$app->session->setFlash('error', 'A solicitação precisa ter pelo menos um item para ser enviada.');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $model->status = 'EM_ANALISE';
        $model->data_envio = date('Y-m-d H:i:s');

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Solicitação enviada para análise com sucesso.');
        } else {
            Yii::$app->session->setFlash('error', 'Não foi possível enviar a solicitação.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionFinalizar($id)
    {
        $model = $this->findModel($id);

        if (!$model->podeFinalizar()) {
            Yii::$app->session->setFlash('error', 'Todos os itens precisam ser analisados antes da finalização.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $todosDeferidos = true;
        $algumDeferido = false;

        foreach ($model->itemEquivalencias as $item) {
            if ($item->parecer !== 'DEFERIDO') {
                $todosDeferidos = false;
            }

            if ($item->parecer === 'DEFERIDO') {
                $algumDeferido = true;
            }
        }

        if ($todosDeferidos) {
            $model->resultado_final = 'DEFERIDO_TOTAL';
        } elseif ($algumDeferido) {
            $model->resultado_final = 'DEFERIDO_PARCIAL';
        } else {
            $model->resultado_final = 'INDEFERIDO_TOTAL';
        }

        $model->status = 'FINALIZADA';
        $model->data_finalizacao = date('Y-m-d H:i:s');

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Solicitação finalizada com sucesso.');
        } else {
            Yii::$app->session->setFlash('error', 'Não foi possível finalizar a solicitação.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

}
