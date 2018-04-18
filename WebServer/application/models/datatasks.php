<?php 
# namespace application\models;
if (!defined('BASEPATH'))
   exit('No direct script access allowed');

class dataTasks extends \CI_Model
{
    // TODO - Insert your code here
    public function __construct()
    {
        // TODO - Insert your code here
        parent::__construct();
    }

    /**
     * @method	: Objective consult data tasks
     * @todo	: Consult desc information
     * @param	: null
     * @return	: Array
     * @since	: 04-2018
     */
    public function ConsultDataTaks()
    {
        $i = 0;
        try {
            # Preparing data for query
            $query = "select * from tasks_backlog order by priority";
            
            $recordSet = $this->db->query($query);
            if ($recordSet->num_rows() > 0){
                foreach ($recordSet->result() as $resultSet){
                    $array[$i] = array( 'ID'              => $resultSet->id,
                                        'TASKS'           => $resultSet->tasks,
                                        'DESCRIPTION'     => $resultSet->description,
                                        'DATA_CONCLUSION' => $resultSet->data_conclusion,
                                        'DATA_START'      => $resultSet->data_start,
                                        'PRIORITY'        => $resultSet->priority,
                                        'FLAG'            => $resultSet->flag,
                                        'TYPE'            => $resultSet->type);
                    $i++;
                }
                
                # Unloading of variable resultSet
                $recordSet->free_result();
                $this->db->close();
                return $array;
                
            }else{
                throw new Exception('Dados nao localizados!');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * @method	: Objective add data tasks
     * @todo	: Add data information
     * @param	: Array
     * @return	: Boolean
     * @since	: 04-2018
     */
    public function AddDataTaks($dataSet)
    {
        $lastUUid = 0;
        $return = null;
        try {
            if(is_array($dataSet)){
                $recordSet = array('tasks'           => $dataSet['TASKS'],
                                   'description'     => $dataSet['DESCRIPTION'],
                                   'data_conclusion' => $dataSet['DATA_CONCLUSION'],
                                   'data_start'      => $dataSet['DATA_START'],
                                   'priority'        => $dataSet['PRIORITY'],
                                   'flag'            => $dataSet['FLAG'],
                                   'type'            => $dataSet['TYPE']);
                
                $return = $this->db->insert('tasks_backlog',$recordSet);
                $lastUUid = $this->db->insert_id();
                $this->db->close();
                return array($return,$lastUUid);
                
            }else{
                throw new Exception('Dados nao inseridos!');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * @method	: Objective update data tasks
     * @todo	: Add data information
     * @param	: Array
     * @return	: Boolean
     * @since	: 04-2018
     */
    public function UpdtDataTaks($id_tasks,$sort_order)
    {
        $return = null;
        try {
            if(($id_tasks != 0)&&($id_tasks != '')&&(($sort_order != null)&&($sort_order != ''))){
                $return = $this->db->replace('tasks_backlog', array('id'=> $id_tasks,'priority'=> $sort_order));
                $this->db->close();
                return $return;
            }else{
                throw new Exception('Voce eh um hacker ou algo do tipo? A tarefa que voce estava tentando editar nao existe.');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * @method	: Objective delete data tasks
     * @todo	: Add data information
     * @param	: Array
     * @return	: Boolean
     * @since	: 04-2018
     */
    public function deltDataTaks($id_tasks)
    {
        $return = null;
        try {
            if(($id_tasks != 0)&&($id_tasks != '')){
                $return = $this->db->delete('tasks_backlog', array('id' => $id_tasks));
                $this->db->close();
                return $return;
            }else{
                throw new Exception('Boas noticias! A tarefa que voce estava tentando excluir nao existe.');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

