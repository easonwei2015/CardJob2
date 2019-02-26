<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Main_model extends CI_Model {
	
    function __construct()
    {
        // 呼叫模型(Model)的建構函數
        parent::__construct();
		$this->load->database();
    }
	
	function get_files_count($query_data)
	{	
		$date_query = (trim($query_data['datepicker'])!== '') ? " AND upload_time like  '%" . $query_data['datepicker'] . "%'" : $date_query = '';
		$user_query = (trim($query_data['users'])!== '') ? " AND author like  '%" . $query_data['users'] . "%'" : $user_query = '';
		
		$query = $this->db->query("SELECT count(1) as count FROM ".$query_data['table']."
									where status <> '2'
									".$date_query."
									".$user_query."
								 ");		
		
		$result = $query->row_array();
		
		
		return $result['count'];
	}	
	
	function get_files_data($query_data)
	{		
	    $date_query = (trim($query_data['datepicker'])!== '') ? " AND upload_time like  '%" . $query_data['datepicker'] . "%'" : $date_query = '';
		$user_query = (trim($query_data['users'])!== '') ? " AND author like  '%" . $query_data['users'] . "%'" : $user_query = '';
		
		$sql = "
			SELECT * FROM ".$query_data['table']."
				where status <> '2'
					".$date_query."
					".$user_query."
				ORDER BY seq DESC
				LIMIT " . $query_data['record_begin'] . ", " . $query_data['show_num'] . "
		";
		
		$query = $this->db->query($sql);

		$result = $query->result_array();	
		
		
		return $result;
	}


	function add_files($insert_data)
	{		
		$data = array(
			
			'file_name' => $insert_data['file_name'],
			'upload_time' => date("Y-m-d H:i:s"),
			'status' => 0,
			'author' => $insert_data['author'],
			'ver' => 1,
		);
		
		if(isset($insert_data['tmp_ver'])){
			$data['tmp_ver'] = $insert_data['tmp_ver'];
		}
		
		$this->db->insert($insert_data['table'], $data);
		
		return true;
	}
	
	function modify_files($modify_data)
	{
		
		$this->db->set('update_time', date("Y-m-d H:i:s"));
		$this->db->set('status', 1);
		$this->db->set('editor', $modify_data['editor']);
		$this->db->set('ver', 'ver+1', FALSE);		
		$this->db->where('file_name', $modify_data['file_name']);
		$this->db->where('status !=' ,2);
		$this->db->update($modify_data['table']);	
		
		
		return;
	}		

	function del_files($del_data)
	{		
		$this->db->set('delete_time', date("Y-m-d H:i:s"));
		$this->db->set('deletor', $del_data['deletor']);	
		$this->db->set('status', 2);	
		$this->db->where('file_name', $del_data['file_name']);
		$this->db->update($del_data['table']);	
		
		
		return;
	}
	
	/**
	 * 上傳 pdf author 需要 on上 原檔初稿(.doc)上傳者的名字
	 *
	 * @author      Easonwei
	 * @link        
	 */
	function get_files_name($query_data)
	{
		$query = $this->db->query("SELECT author 
									FROM `card_upload` 
										WHERE file_name like '".$query_data['file_name']."%' 
											and status <> '2' 
									limit 0,1"
		);
		
		$result = $query->row_array();
		
		return $result['author'];
	}
	
	/**
	 * 工作卡上傳 檢查 同檔名
	 *
	 * @author      Easonwei
	 * @link        
	 */
	function same_files_count($query_data)
	{		
		$query = $this->db->query("SELECT count(1) as count
									FROM `card_upload` 
									WHERE file_name like '".$query_data['file_name']."%'
										and status <> '2'
		");
		
		$result = $query->row_array();
		$result['tmp_ver'] = $this->_same_file_ver($query_data);
		
		return $result;		
	}
	
	/**
	 * 工作卡上傳 檢查 同上傳者
	 *
	 * @author      Easonwei
	 * @link        
	 */
	function same_files_user($query_data)
	{
		$sql = "SELECT count(1) as count, file_name
									FROM `card_upload` 
									WHERE file_name like '".$query_data['file_name']."%' 
										and author='".$query_data['name']."' 
										and status <> '2' 
									group by file_name
									limit 0,1";
		
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	/**
	 * 目前有上傳檔案的上傳人
	 *
	 * @author      Easonwei
	 * @link        
	 */
	function get_all_users($query_data)
	{
		$sql ="SELECT author 
					FROM ".$query_data['table']."
						where status <> '2' 
					group by author";
		
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}	

	function _same_file_ver($query_data)
	{
		
		$query = $this->db->query("
									SELECT tmp_ver
										FROM `card_upload` 
											WHERE file_name like '".$query_data['file_name']."%'
												and status <> '2' 
									order by seq desc 
									limit 0,1		
		");
		
		$result = $query->row_array();
		
		return $result['tmp_ver'];		
	}	
	
	/**
	 * 登入紀錄
	 *
	 * @author      Easonwei
	 * @link        
	 */
    function insert_log($result)
    {		
		$data = array(
		   'user_id' => $result['user_id'] ,
		   'user_name' => $result['user_name'],
		   'ip' => $result['ip'],
		   'datetime' => date("Y-m-d H:i:s"),
		);
		
		$this->db->insert('loginlog', $data);
		
		
		return ;
    }
	
	/**
	 * 操作紀錄
	 *
	 * @author      Easonwei
	 * @link        
	 */
    function operat_log($result)
    {		
		$data = array(
		   'file_name' => $result['file_name'] ,
		   'time' => date("Y-m-d H:i:s"),
		   'user' => $result['user'],
		   'action' => $result['action'],
		   'post_location' => $result['post_location']  
		);
		
		$this->db->insert('operat_log', $data);
		
		
		return ;
    }
	
	/**
	 * 排行榜
	 *
	 * @author      Easonwei
	 * @link        
	 */	
	function files_ranking()
	{		
		$sql = "
			SELECT author,count(1) count, upload_time 
				FROM `card_upload` 
				WHERE status <> '2' 
			group by author 
			ORDER BY COUNT DESC,upload_time ASC
		";
		
		$query = $this->db->query($sql);

		$result = $query->result_array();	
		
		
		return $result;
	}	
	
	/**
	 * 排行榜
	 *
	 * @author      Easonwei
	 * @link        
	 */	
	function review_ranking()
	{		
		$sql = "
			SELECT author,count(1) count 
				FROM `review_card` 
				WHERE status <> '2' 
			group by author 
			ORDER BY COUNT DESC
		";
		
		$query = $this->db->query($sql);

		$result = $query->result_array();	
		
		
		return $result;
	}		

}