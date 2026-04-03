<?php

namespace app\controllers;

use app\models\ItemEquivalencia;
use app\models\LogAcao;
use Yii;
use app\models\SolicitacaoAproveitamento;
use app\models\SolicitacaoAproveitamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

class SolicitacaoAproveitamentoController extends Controller
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
                        'enviar' => ['POST'],
                        'finalizar' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel = new SolicitacaoAproveitamentoSearch();
        $query = SolicitacaoAproveitamento::find()
            ->with(['estudante', 'coordenador'])
            ->orderBy(['id' => SORT_DESC]);

        $usuario = Yii::$app->user->identity;

        if (!$usuario) {
            throw new ForbiddenHttpException('Acesso negado.');
        }

        // ADMIN vê tudo
        if ($usuario->isAdmin()) {
            // sem filtro
        }

        // ALUNO vê só as próprias
        elseif ($usuario->isAluno()) {
            if (!$usuario->estudante_id) {
                throw new ForbiddenHttpException('Usuário aluno sem vínculo com estudante.');
            }

            $query->andWhere(['estudante_id' => $usuario->estudante_id]);
        }

        // COORDENADOR vê só as dele
        elseif ($usuario->isCoordenador()) {
            if (!$usuario->coordenador_id) {
                throw new ForbiddenHttpException('Usuário coordenador sem vínculo com coordenador.');
            }

            $query->andWhere(['coordenador_id' => $usuario->coordenador_id]);
        }

        else {
            throw new ForbiddenHttpException('Perfil sem permissão.');
        }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->verificarPermissaoSolicitacao($model);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $usuario = Yii::$app->user->identity;

        if (!$usuario) {
            throw new ForbiddenHttpException('Acesso negado.');
        }

        // Apenas ALUNO e ADMIN podem criar solicitação
        if (!$usuario->isAluno() && !$usuario->isAdmin()) {
            throw new ForbiddenHttpException('Você não tem permissão para criar solicitações.');
        }

        $model = new SolicitacaoAproveitamento();

        // Se for aluno, força o estudante_id dele
        if ($usuario->isAluno()) {
            if (!$usuario->estudante_id || !$usuario->estudante) {
                throw new ForbiddenHttpException('Usuário aluno sem estudante vinculado.');
            }

            $model->estudante_id = $usuario->estudante_id;

            // Coordenador automático pelo curso do aluno
            $cursoId = $usuario->estudante->curso_id;
            $coordenador = \app\models\Coordenador::findOne(['curso_id' => $cursoId]);

            if ($coordenador) {
                $model->coordenador_id = $coordenador->id;
            }
        }

        if ($this->request->isPost && $model->load($this->request->post())) {

            // Segurança: aluno não pode alterar estudante/coordenador via POST
            if ($usuario->isAluno()) {
                $model->estudante_id = $usuario->estudante_id;

                $cursoId = $usuario->estudante->curso_id;
                $coordenador = \app\models\Coordenador::findOne(['curso_id' => $cursoId]);
                $model->coordenador_id = $coordenador ? $coordenador->id : null;
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $model->registrarAcao('Solicitação criada com protocolo ' . $model->numero_protocolo);
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Solicitação criada com sucesso.');
                    return $this->redirect(['update', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Erro ao criar solicitação.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Erro ao criar solicitação: ' . $e->getMessage());
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
        $this->verificarPermissaoSolicitacao($model);

        $usuario = Yii::$app->user->identity;

        // Coordenador não edita solicitação como formulário de aluno
        if ($usuario->isCoordenador()) {
            throw new ForbiddenHttpException('Coordenador não pode editar a solicitação dessa forma.');
        }

        if (!$model->podeEditar()) {
            Yii::$app->session->setFlash('error', 'Esta solicitação não pode mais ser editada.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($this->request->isPost && $model->load($this->request->post())) {

            // segurança: aluno não altera dono/coordenador
            if ($usuario->isAluno()) {
                $model->estudante_id = $usuario->estudante_id;

                $cursoId = $usuario->estudante->curso_id;
                $coordenador = \app\models\Coordenador::findOne(['curso_id' => $cursoId]);
                $model->coordenador_id = $coordenador ? $coordenador->id : null;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Solicitação atualizada com sucesso.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->verificarPermissaoSolicitacao($model);

        $usuario = Yii::$app->user->identity;

        // idealmente só admin pode excluir
        if (!$usuario->isAdmin()) {
            throw new ForbiddenHttpException('Somente o administrador pode excluir solicitações.');
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionEnviar($id)
    {
        $model = $this->findModel($id);
        $this->verificarPermissaoSolicitacao($model);

        $usuario = Yii::$app->user->identity;

        // só aluno dono ou admin
        if (!$usuario->isAluno() && !$usuario->isAdmin()) {
            throw new ForbiddenHttpException('Você não tem permissão para enviar esta solicitação.');
        }

        if (!$model->podeEnviar()) {
            Yii::$app->session->setFlash('error', 'A solicitação precisa ter pelo menos um item para ser enviada.');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->status = 'EM_ANALISE';
            $model->data_envio = date('Y-m-d H:i:s');

            if ($model->save(false)) {
                $model->registrarAcao('Solicitação enviada para análise. ' . count($model->itemEquivalencias) . ' item(ns).');
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Solicitação enviada para análise com sucesso.');
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Não foi possível enviar a solicitação.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Erro ao enviar solicitação: ' . $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionFinalizar($id)
    {
        $model = $this->findModel($id);
        $this->verificarPermissaoSolicitacao($model);

        $usuario = Yii::$app->user->identity;

        // SOMENTE coordenador da solicitação ou admin pode finalizar
        if (!$usuario->isCoordenador() && !$usuario->isAdmin()) {
            throw new ForbiddenHttpException('Somente o coordenador pode finalizar a solicitação.');
        }

        if (!$model->podeFinalizar()) {
            Yii::$app->session->setFlash('error', 'Todos os itens precisam ser analisados antes da finalização.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $todosDeferidos = true;
            $algumDeferido = false;
            $deferidos = 0;
            $indeferidos = 0;

            foreach ($model->itemEquivalencias as $item) {
                if ($item->parecer !== 'DEFERIDO') {
                    $todosDeferidos = false;
                    $indeferidos++;
                } else {
                    $algumDeferido = true;
                    $deferidos++;
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
                $model->registrarAcao(
                    "Solicitação finalizada com resultado {$model->resultado_final}. " .
                    "Deferidos: {$deferidos}, Indeferidos: {$indeferidos}"
                );
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Solicitação finalizada com sucesso.');
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Não foi possível finalizar a solicitação.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Erro ao finalizar solicitação: ' . $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    protected function findModel($id)
    {
        if (($model = SolicitacaoAproveitamento::find()
            ->with(['estudante', 'coordenador', 'itemEquivalencias', 'logAcaos'])
            ->where(['id' => $id])
            ->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A solicitação solicitada não existe.');
    }

    protected function verificarPermissaoSolicitacao($model)
    {
        $usuario = Yii::$app->user->identity;

        if (!$usuario) {
            throw new ForbiddenHttpException('Acesso negado.');
        }

        if ($usuario->isAdmin()) {
            return true;
        }

        if ($usuario->isAluno()) {
            if ((int)$model->estudante_id !== (int)$usuario->estudante_id) {
                throw new ForbiddenHttpException('Você não tem permissão para acessar esta solicitação.');
            }
            return true;
        }

        if ($usuario->isCoordenador()) {
            if ((int)$model->coordenador_id !== (int)$usuario->coordenador_id) {
                throw new ForbiddenHttpException('Você não tem permissão para acessar esta solicitação.');
            }
            return true;
        }

        throw new ForbiddenHttpException('Perfil sem permissão.');
    }
}