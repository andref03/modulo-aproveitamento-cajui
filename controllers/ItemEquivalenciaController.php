<?php

namespace app\controllers;

use Yii;
use app\models\ItemEquivalencia;
use app\models\ItemEquivalenciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

class ItemEquivalenciaController extends Controller
{
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

    public function actionIndex()
    {
        $searchModel = new ItemEquivalenciaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $usuario = Yii::$app->user->identity;
        $query = $dataProvider->query;

        if ($usuario->isAluno()) {
            $query->joinWith('solicitacao')
                  ->andWhere(['solicitacao_aproveitamento.estudante_id' => $usuario->estudante_id]);
        } elseif ($usuario->isCoordenador()) {
            $query->joinWith('solicitacao')
                  ->andWhere(['solicitacao_aproveitamento.coordenador_id' => $usuario->coordenador_id]);
        }
        // admin vê tudo

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->verificarPermissaoItem($model, 'view');

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate($solicitacao_id = null)
    {
        $usuario = Yii::$app->user->identity;

        if (!$usuario->isAluno() && !$usuario->isAdmin()) {
            throw new ForbiddenHttpException('Apenas alunos e administradores podem cadastrar itens.');
        }

        $model = new ItemEquivalencia();

        if ($solicitacao_id !== null) {
            $model->solicitacao_id = $solicitacao_id;

            $solicitacao = \app\models\SolicitacaoAproveitamento::findOne($solicitacao_id);

            if (!$solicitacao) {
                throw new NotFoundHttpException('Solicitação não encontrada.');
            }

            $this->verificarPermissaoSolicitacaoDoItem($solicitacao, 'create');

            if (!$solicitacao->podeEditar()) {
                Yii::$app->session->setFlash('error', 'Não é possível adicionar itens a esta solicitação.');
                return $this->redirect(['solicitacao-aproveitamento/view', 'id' => $solicitacao_id]);
            }
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            $solicitacao = \app\models\SolicitacaoAproveitamento::findOne($model->solicitacao_id);

            if (!$solicitacao) {
                throw new NotFoundHttpException('Solicitação não encontrada.');
            }

            $this->verificarPermissaoSolicitacaoDoItem($solicitacao, 'create');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Item adicionado com sucesso.');
                return $this->redirect(['solicitacao-aproveitamento/update', 'id' => $model->solicitacao_id]);
            }
        }

        $model->loadDefaultValues();

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $solicitacao = $model->solicitacao;

        $this->verificarPermissaoItem($model, 'update');

        $parecerAnterior = $model->parecer;

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->load($this->request->post()) && $model->save()) {

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

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $solicitacao = $model->solicitacao;

        $this->verificarPermissaoItem($model, 'delete');

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

    protected function findModel($id)
    {
        if (($model = ItemEquivalencia::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function verificarPermissaoItem($item, $acao = 'view')
    {
        $usuario = Yii::$app->user->identity;
        $solicitacao = $item->solicitacao;

        if (!$solicitacao) {
            throw new ForbiddenHttpException('Item sem solicitação vinculada.');
        }

        if ($usuario->isAdmin()) {
            return true;
        }

        if ($usuario->isAluno()) {
            if ((int)$solicitacao->estudante_id !== (int)$usuario->estudante_id) {
                throw new ForbiddenHttpException('Você não tem permissão para acessar este item.');
            }

            // aluno só mexe enquanto estiver em edição
            if (in_array($acao, ['update', 'delete']) && !$solicitacao->podeEditar()) {
                throw new ForbiddenHttpException('Você não pode alterar itens após envio para análise.');
            }

            return true;
        }

        if ($usuario->isCoordenador()) {
            if ((int)$solicitacao->coordenador_id !== (int)$usuario->coordenador_id) {
                throw new ForbiddenHttpException('Você não tem permissão para acessar este item.');
            }

            // coordenador não cria/exclui item acadêmico do aluno
            if (in_array($acao, ['create', 'delete'])) {
                throw new ForbiddenHttpException('Você não tem permissão para executar esta ação.');
            }

            return true;
        }

        throw new ForbiddenHttpException('Acesso negado.');
    }

    protected function verificarPermissaoSolicitacaoDoItem($solicitacao, $acao = 'create')
    {
        $usuario = Yii::$app->user->identity;

        if ($usuario->isAdmin()) {
            return true;
        }

        if ($usuario->isAluno()) {
            if ((int)$solicitacao->estudante_id !== (int)$usuario->estudante_id) {
                throw new ForbiddenHttpException('Você não tem permissão para usar esta solicitação.');
            }

            return true;
        }

        throw new ForbiddenHttpException('Acesso negado.');
    }
}