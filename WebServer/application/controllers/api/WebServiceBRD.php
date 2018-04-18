<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
class WebServiceBRD extends REST_Controller {
//class WebServiceBRD extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        // Limites requests por houra e por usuário (key)
        $this->methods['tasks_get' ]['limit'] = 100;
        $this->methods['tasksPost']['limit'] = 100;
        $this->methods['tasks_updt']['limit'] = 150;
        $this->methods['tasks_delt']['limit'] = 150;
        
        $this->load->database();
        $this->load->model('datatasks','SetTasks',TRUE);
        $this->load->model('dataTasks','GetTasks',TRUE);
    }

    /**
     * @todo: Listar todas as tarefas do backlog
     * @param: null or ID Taks
     * @author: Marcelo Oliveira.
     * @return: array[json]
     */
    public function tasks_get(){
        $i = 0;
        $arrayTasks = null;
        try {
            $arrayTasks = $this->GetTasks->ConsultDataTaks();
            if (is_array($arrayTasks)){
                
                //Descarregar dados do Modelo: data_model.
                //Parametros repassados da view...
                $id = $this->get('id');
                
                // Se o parâmetro id não existir, retorne todos as tarefas
                if ($id == NULL){
                
                    //Verifique se o armazenamento de dados dos usuários contém usuários (caso o resultado do banco de dados retorne NULL)
                    if (count($arrayTasks) > 0){
                        //Definir a resposta e sair
                        //FOUND(200)
                        $this->response($arrayTasks, REST_Controller::HTTP_OK);
                    }
                    else{
                        //Definir a resposta e sair
                        //NOT_FOUND(404)
                        $this->response([
                            'status' => FALSE,
                            'message' => 'No tasks were found'
                        ], REST_Controller::HTTP_NOT_FOUND);
                    }
                }
                else{                
                    //Retorne um único registro para a tasks específica: Validar o id.
                    $id = (int) $id;
                    if ($id <= 0){
                        //ID invalido: responsa e saia.
                        //BAD_REQUEST (400)
                        $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
                    }
                    else{
                        // Obter a tasks da matriz, usando o id como chave para recuperação.
                        // Normalmente, um modelo deve ser saida dos dados.
                        $task = NULL;
                        if(!empty($arrayTasks)){
                            foreach ($arrayTasks as $key => $value){
                                if (isset($value['ID']) && $value['ID'] == $id){
                                    $task = $value;
                                }
                            }
                            if(!empty($task)){
                                //FOUND(200)
                                $this->set_response($task, REST_Controller::HTTP_OK);
                            }
                            else{
                                //NOT_FOUND (404)
                                $this->set_response([
                                    'status' => FALSE,
                                    'message' => 'Task could not be found'
                                ], REST_Controller::HTTP_NOT_FOUND);
                            }
                        }
                    }
                }
            }
            else{
                throw new Exception('Uau. Voce nao tem mais nada para fazer. Aproveite o resto do seu dia: '.$arrayTasks);
            }
        } catch (Exception $e) {
           print $e->getMessage();
        }
    }
    
    /**
     * @todo: Incluir uma tarefa no backlog
     * @param: null
     * @return: boolean, array[json]
     */
    public function tasksPost(){
        
        date_default_timezone_set('America/Sao_Paulo');
        
        $mes = date('m');
        $ano = date("Y");
        
        $date_end = date("t", mktime(0,0,0,$mes,'01',$ano));
        $date_created = date('Y-m-d');
        
        $message= null;
        $return = null;
        
        $prior  = $this->input->post('sort_order');
        $conts  = $this->input->post('content');
        $flags  = $this->input->post('done');
        $tasks  = $this->input->post('name');
        $type   = $this->input->post('type');
        
        try{
            if(($tasks != null)&&($tasks != '')){
                $arrayTasks = array('TASKS'           => $tasks,
                                    'DESCRIPTION'     => $conts,
                                    'DATA_CONCLUSION' => $date_end,
                                    'DATA_START'      => $date_created,
                                    'PRIORITY'        => $prior,
                                    'FLAG'            => $flags,
                                    'TYPE'            => $type);
                list($return,$uuid) = $this->SetTasks->AddDataTaks($arrayTasks);
               // $this->set_response(array("uuid" => $uuid,"type" => $type,"content" => $conts,"sort_order"=> $prior,"done" => $flags,"message" => $return,"date_created" => $date_created), REST_Controller::HTTP_CREATED);
            }
            else{
                throw new Exception('Movimentacao incorreta! Tente remover a tarefa em vez de excluir seu conteudo.');
            }
        }catch (Exception $e){
            print $e->getMessage();
        }
    }

    /**
     * @todo: Apagar uma tarefa do backlog
     * @param: ID(int) da tarefa(tabela: tasks)
     * @return: boolean, array[json]
     */
    public function tasks_delt(){
       
       $message= null;
       $return = null;
       $id = $this->input->post('id');
       $nm = $this->input->post('name');
       
       try{
          if (($id <= 0)||($id == '')||($id == null)){
             $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
          }else{
            $return = $this->SetTasks->deltDataTaks($id);
            $this->set_response(array('id' => $id, 'message' => 'Process successfully executed','return' => $return), REST_Controller::HTTP_NO_CONTENT);
         }
       }catch (Exception $e){
           print $e->getMessage();
       }
    }
    
    /**
     * @todo: Alterar a prioridade de uma tarefa
     * @param: ID(int) do tarefa(tabela: tasks_backlog)
     * @return: array[json]
     */
    public function tasks_updt(){
        
        $message= null;
        $return = null;
        $id = (int) $this->input->post('id');
        $so = $this->input->post('sort_order');
        
        try{
            if (($id <= 0)||($id == '')||($id == null)){
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            }else{
                $return = $this->SetTasks->UpdtDataTaks($id,$so);
                $this->set_response(array('id' => $id, 'message' => 'Process successfully executed','return' => $return), REST_Controller::HTTP_NO_CONTENT);
            }
        }catch (Exception $e){
            print $e->getMessage();
        }
    }  
}
