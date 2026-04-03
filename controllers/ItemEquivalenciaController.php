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
        $solicitacao = $model->solicitacao;

        // Só bloqueia se estiver em um estado inválido
        if (!in_array($solicitacao->status, ['EM_EDICAO', 'EM_ANALISE', 'FINALIZADA'])) {
            Yii::$app->session->setFlash('error', 'Não é possível editar este item no estado atual da solicitação.');
            return $this->redirect(['solicitacao-aproveitamento/view', 'id' => $solicitacao->id]);
        }

        $parecerAnterior = $model->parecer;

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->load($this->request->post()) && $model->save()) {

                    // Registra log se o parecer mudou
                    if ($parecerAnterior !== $model->parecer) {
                        $descricao = "Item #{$model->id} analisado. Disciplina: {$model->disciplina_origem_nome}. Parecer: {$model->parecerFormatado}";

                        if (!empty($model->justificativa)) {
                            $descricao .= ". Justificativa: " . mb_substr($model->justificativa, 0, 100);
                        }

                        if (method_exists($model->solicitacao, 'registrarAcao')) {
                            $model->solicitacao->registrarAcao($descricao);
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Item atualizado com sucesso.');

                    // Se estiver finalizada ou em análise, faz mais sentido voltar para view
                    if (in_array($solicitacao->status, ['EM_ANALISE', 'FINALIZADA'])) {
                        return $this->redirect(['solicitacao-aproveitamento/view', 'id' => $model->solicitacao_id]);
                    }

                    return $this->redirect(['solicitacao-aproveitamento/update', 'id' => $model->solicitacao_id]);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Não foi possível salvar o item. Verifique os dados informados.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Erro ao salvar item: ' . $e->getMessage());
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
        $solicitacao = $model->solicitacao;

        if (!$solicitacao->podeEditar()) {
            Yii::$app->session->setFlash('error', 'Não é permitido excluir itens de uma solicitação que já foi enviada para análise.');
            return $this->redirect(['solicitacao-aproveitamento/view', 'id' => $solicitacao->id]);
        }

        if (count($solicitacao->itemEquivalencias) <= 1) {
            Yii::$app->session->setFlash('error', 'A solicitação deve possuir pelo menos um item de equivalência.');
            return $this->redirect(['solicitacao-aproveitamento/update', 'id' => $solicitacao->id]);
        }

        $solicitacaoId = $solicitacao->id;
        $model->delete();

        Yii::$app->session->setFlash('success', 'Item excluído com sucesso.');
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
