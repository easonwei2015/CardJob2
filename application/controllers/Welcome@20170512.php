<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*  
	工作卡上傳系統 修訂紀錄
	-------------------------------------------------------------------------------------------------------
	20170503 
		modify
			● 若上傳檔名相同，則覆蓋檔案，並更新版本號。

	-------------------------------------------------------------------------------------------------------
	20170504    
		modify 
			● 工作卡上傳若 ( 同檔名、不同上傳者 )，則不覆蓋檔案，將檔名更新成 -> 檔名_2.doc ，
			  若同檔名同上傳者 -> 視為版本更新。
					
			● 將 function do_upload 拆成2個，於 table card_upload 新增 tmp_ver 欄位，便於紀錄目前( 相同檔名 不同上傳者 )的版本號
	-------------------------------------------------------------------------------------------------------
	20170505    
		modify 
			● 將使用者操作紀錄在 table log
	-------------------------------------------------------------------------------------------------------
	201700510
		add 
			● 新增上傳人、上傳時間 查詢	
	-------------------------------------------------------------------------------------------------------
	20170511
		modify 
			● 使用者不能刪檔案
*/

class Welcome extends CI_Controller {

	var $index_base_url = '';
	var $host_name = '玉里慈濟醫院工作卡上傳系統';
	var $manage_group = array('林可人');
	
    function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->model('main_model');
		$this->load->helper('url');
		$this->index_base_url = base_url() . 'welcome/';
    }
	
	/**
	 * 首頁
	 *
	 * @author      Easonwei
	 * @link        http://localhost/welcome/
	 */		
	public function index()
	{
		$this->_check_login();		
		$session = $this->_get_session();
		
		$data = array();
		$data['base_url'] = base_url();
		$data['index_base_url'] = $this->index_base_url;
		$data['host_name'] = $this->host_name;
		$data['chName'] = $session['ssnUserName'];
		$data['title'] = $this->host_name;
		$data['page_name'] = '公告';
		
		$this->load->view('sky/head.php', $data);
		$this->load->view('sky/load_element.php', $data);	
		$this->load->view('sky/body.php');		
		$this->load->view('sky/topbar.php', $data);		
		$this->load->view('sky/menu.php', $data);
		$this->load->view('sky/index.php', $data);
		$this->load->view('sky/footer.php', $data);
		$this->load->view('sky/external.php', $data);
		$this->load->view('sky/body2.php');
	}
	/**
	 * 工作卡上傳
	 *
	 * @author      Easonwei
	 * @link        http://localhost/welcome/card_uploads
	 */	
	public function card_upload()
	{
		$this->_check_login();		
		$get_data = $this->input->get();
		$session = $this->_get_session();
		$errors=array();
		$show_num = 10; // 每頁顯示筆數	
		(isset($get_data['page'])) ? $now_Page = $get_data['page']: $now_Page = 0 ;
		$last_page = $now_Page-1;
		$next_page = $now_Page+1;
		$query_data = array();
		$query_data['table'] = $this->router->fetch_method();
		$query_data['users'] = (isset($get_data['users']))?$get_data['users']:"";
		$query_data['datepicker'] = (isset($get_data['datepicker']))?$get_data['datepicker']:"";
		$files_count = $this->main_model->get_files_count($query_data);
		
		$record_begin = $now_Page * $show_num;	// 起始指標		
		$PageTotal = ceil($files_count/$show_num); // 計算總頁數
		
		$query_data['record_begin'] = $record_begin;
		$query_data['show_num'] = $show_num;
		
		
		$files_data = $this->main_model->get_files_data($query_data);
		$all_users = $this->main_model->get_all_users($query_data); // 上傳過檔案的上傳人
		
		// 組 上傳過檔案人的下拉選單		
		$select_option = $this->_athor_option($all_users);
		
		$data = array();
		
		$data['base_url'] = base_url();
		$data['index_base_url'] = $this->index_base_url;
		$data['host_name'] = $this->host_name;
		$data['chName'] = $session['ssnUserName'];
		$data['title'] = $this->host_name;
		$data['page_name'] = '工作卡上傳';
		$data['files_data'] = $files_data;
		$data['now_page'] = $now_Page;
		$data['page_total'] = $PageTotal;
		$data['method_name'] = $this->router->fetch_method();
		$data['all_users'] = $select_option;
		$data['datepicker'] = (isset($get_data['datepicker']))? $get_data['datepicker']:'';
		$data['users'] = (isset($get_data['users']))? $get_data['users']:'';
		$data['show_last_page'] = ($now_Page>0)? "<li><a href='".$this->index_base_url."card_upload?page=".$last_page."&datepicker=".$data['datepicker']."&users=".$data['users']."'>上一頁</a></li>" : "";
		$data['show_next_page']= ($PageTotal>$next_page)? "<li><a href='".$this->index_base_url."card_upload?page=".$next_page."&datepicker=".$data['datepicker']."&users=".$data['users']."'>下一頁</a></li>" : "";
		$data['manage_group'] = (in_array(trim($session['ssnUserName']), $this->manage_group)) ? true : false;
		//$data['errors'] = $errors;
		
		$this->load->view('sky/head.php', $data);
		$this->load->view('sky/load_element.php', $data);	
		$this->load->view('sky/body.php');		
		$this->load->view('sky/topbar.php', $data);		
		$this->load->view('sky/menu.php', $data);
		$this->load->view('sky/card_upload.php', $data);
		$this->load->view('sky/footer.php', $data);
		$this->load->view('sky/external.php', $data);
		$this->load->view('sky/body2.php');		
	}
	
	/**
	 * 已審核工作卡
	 *
	 * @author      Easonwei
	 * @link        http://localhost/welcome/review_card
	 */		
	public function review_card()
	{
		$this->_check_login();		
		$get_data = $this->input->get();
		$session = $this->_get_session();
		$errors=array();
		$show_num = 20; // 每頁顯示筆數	
		(isset($get_data['page'])) ? $now_Page = $get_data['page']: $now_Page = 0 ;
		$last_page = $now_Page-1;
		$next_page = $now_Page+1;
		$query_data = array();
		$query_data['table'] = $this->router->fetch_method();
		$query_data['users'] = (isset($get_data['users']))?$get_data['users']:"";
		$query_data['datepicker'] = (isset($get_data['datepicker']))?$get_data['datepicker']:"";
		$files_count = $this->main_model->get_files_count($query_data);
		
		$record_begin = $now_Page * $show_num;	// 本頁之起始指標		
		$PageTotal = ceil($files_count/$show_num); // 計算總頁數
		
		$query_data['record_begin'] = $record_begin;
		$query_data['show_num'] = $show_num;
		
		$files_data = $this->main_model->get_files_data($query_data);
		$all_users = $this->main_model->get_all_users($query_data); // 上傳過檔案的上傳人
		
		// 組 上傳過檔案人的下拉選單		
		$select_option = $this->_athor_option($all_users);
				
		$data = array();
		
		$data['base_url'] = base_url();
		$data['index_base_url'] = $this->index_base_url;
		$data['host_name'] = $this->host_name;
		$data['chName'] = $session['ssnUserName'];
		$data['title'] = $this->host_name;
		$data['page_name'] = '已審核工作卡';
		$data['files_data'] = $files_data;
		$data['now_page'] = $now_Page;
		$data['page_total'] = $PageTotal;
		$data['method_name'] = $this->router->fetch_method();
		//$data['errors'] = $errors;
		$data['all_users'] = $select_option;
		$data['datepicker'] = (isset($get_data['datepicker']))? $get_data['datepicker']:'';
		$data['users'] = (isset($get_data['users']))? $get_data['users']:'';
		$data['show_last_page'] = ($now_Page>0)? "<li><a href='".$this->index_base_url."card_upload?page=".$last_page."&datepicker=".$data['datepicker']."&users=".$data['users']."'>上一頁</a></li>" : "";
		$data['show_next_page']= ($PageTotal>$next_page)? "<li><a href='".$this->index_base_url."card_upload?page=".$next_page."&datepicker=".$data['datepicker']."&users=".$data['users']."'>下一頁</a></li>" : "";
		$data['manage_group'] = (in_array(trim($session['ssnUserName']), $this->manage_group)) ? true : false;		
		
		$this->load->view('sky/head.php', $data);
		$this->load->view('sky/load_element.php', $data);	
		$this->load->view('sky/body.php');		
		$this->load->view('sky/topbar.php', $data);		
		$this->load->view('sky/menu.php', $data);
		$this->load->view('sky/review_card.php', $data);
		$this->load->view('sky/footer.php', $data);
		$this->load->view('sky/external.php', $data);
		$this->load->view('sky/body2.php');		
	}	

	/**
	 * 上傳工作卡 功能
	 *
	 * @author      Easonwei
	 * @link        http://localhost/welcome/do_upload
	 */		
	public function do_upload(){
		$post_data = $this->input->post();
		
		if(isset($_FILES['files'])){
			$session = $this->_get_session();
			
			foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
				//$file_name = $key.$_FILES['files']['name'][$key];
				$file_name = $_FILES['files']['name'][$key];
				$file_size =$_FILES['files']['size'][$key];
				$file_tmp =$_FILES['files']['tmp_name'][$key];
				$file_type=$_FILES['files']['type'][$key]; 
				
				// 設定開放上傳檔案格式
				$allow_type = array('application/msword',
								'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
							   );
				$desired_dir="assets/upload_data/card_upload";
				
				if($file_size > 41943040){
					$errors[]='檔案大小要小於 40 MB';
				}elseif (!in_array($file_type, $allow_type)){
					$errors[]='檔案格式不符合規定';
				}				

				if(empty($errors)==true){
					if(is_dir($desired_dir)==false){
						//mkdir("$desired_dir", 0777);
						mkdir($desired_dir, 0, true);
					}
					//add files
					if(is_file("$desired_dir/".$file_name)==false){
					
						move_uploaded_file($file_tmp,$desired_dir."/".$file_name);
						
						$insert_data = array(
											'file_name' =>$file_name,
											'author' =>$session['ssnUserName'],
											'table' => $post_data['table'],
											'tmp_ver' => 1
						);
						
						$this->main_model->add_files($insert_data);
						
						// 新增動作寫入log
						$this->_action_insert_log($file_name, 'add', $session['ssnUserName'], '1');
						
					// modify files
					}else{
						$same_query_data=array();
						$same_query_data['name'] = trim($session['ssnUserName']);
						
						$filter_arr = array(".doc" => "",".docx" => "");
						$same_query_data['file_name'] = strtr($file_name, $filter_arr);
						
						$same_files = $this->main_model->same_files_count($same_query_data);
						$same_user = $this->main_model->same_files_user($same_query_data);
						//var_dump($same_query_data);
						//var_dump($same_files);
						//var_dump($same_user);
		
						// 有人上傳過此檔名，自己也上傳過
						if($same_files['count'] > 0 && $same_user[0]['count'] > 0){
							$file_name = $same_user[0]['file_name'];
						// 有人上傳過此檔名，自己還未上傳過
						}elseif($same_files['count'] > 0 && empty($same_user)){
							$ver=$same_files['tmp_ver']+1; //版本號
							$ver_str = '_'.$ver; // 附加檔名
							$filter_arr = array(".doc" => $ver_str.".doc",".docx" => $ver_str.".docx");
							$ver_name = strtr($file_name, $filter_arr);
							
							move_uploaded_file($file_tmp,$desired_dir."/".$ver_name);	//add files					
							$insert_data = array(
											'file_name' =>$ver_name,
											'author' =>$session['ssnUserName'],
											'table' => $post_data['table'],
											'tmp_ver' => $ver
							);
							
							$this->main_model->add_files($insert_data);
							// 新增版本動作寫入log
							$this->_action_insert_log($ver_name, 'add_ver', $session['ssnUserName'], '1');								
							
							continue;
						}
						
						$new_dir= $desired_dir."/".$file_name;
						 rename($file_tmp,$new_dir);
						 
						 $modify_data = array(
											'file_name' =>$file_name,
											'editor' => $session['ssnUserName'],
											'table' => $post_data['table']											
						);
						 $this->main_model->modify_files($modify_data);
						// 修改動作寫入log
						$this->_action_insert_log($file_name, 'modify', $session['ssnUserName'], '1');								
					}
				}else{
					continue;
				}
			}
			if(empty($errors)==true){	
			}
		}		
		$url= $this->index_base_url.$post_data['table'];
		header("Location:$url" );
		return false;
	}
	
	
	/**
	 * 審核工作卡 上傳 功能
	 *
	 * @author      Easonwei
	 * @link        http://localhost/welcome/do_upload
	 */		
	public function review_do_upload(){
		$post_data = $this->input->post();
		//$ori_author='';
		$page_errors = array();
		if(count($_FILES['files']['name'])>1){
			$page_errors[] = '因為目前制定人為手動新增，每次僅能上傳 1 個 PDF檔案';
		}
		if(trim($post_data['developers']) ==''){
			$page_errors[] = '制定人不能為空';
		}
		
		$allow_type = array('application/pdf');
		if (!in_array($_FILES['files']['type'][0], $allow_type)){
			$page_errors[]='檔案格式不符合規定';
		}	
		
		if(count($page_errors) > 0){
			$msg = '';
			$msg_str = '';
			foreach($page_errors as $msg){
				$msg_str .= $msg . '<br>';
			}
			
			$url= $this->index_base_url.$post_data['table'].'?msg_str='.$msg_str;
			header("Location:$url" );
			return false;
		}
		
		if(isset($_FILES['files'])){
			$session = $this->_get_session();			
			foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
				//$file_name = $key.$_FILES['files']['name'][$key];
				$file_name = $_FILES['files']['name'][$key];
				$file_size =$_FILES['files']['size'][$key];
				$file_tmp =$_FILES['files']['tmp_name'][$key];
				$file_type=$_FILES['files']['type'][$key]; 
				
				// 設定開放上傳檔案格式
				$allow_type = array('application/pdf');
				$desired_dir="assets/upload_data/review_card";
				// 已審核工作卡 上傳者要 on 上 初版(.doc)的作者名
				$query_data=array();
				$query_data['file_name'] = str_replace(".pdf","",$file_name);
				
				//$ori_author = $this->main_model->get_files_name($query_data);
				
				if($file_size > 41943040){
					$errors[]='檔案大小要小於 40 MB';
				}elseif (!in_array($file_type, $allow_type)){
					$errors[]='檔案格式不符合規定';
				}				

				if(empty($errors)==true){
					if(is_dir($desired_dir)==false){
						//mkdir("$desired_dir", 0777);
						mkdir($desired_dir, 0, true);
					}
					//add files
					if(is_file("$desired_dir/".$file_name)==false){
					
						move_uploaded_file($file_tmp,$desired_dir."/".$file_name);
						
						$insert_data = array(
											'file_name' =>$file_name,
											'table' => $post_data['table'],
											'author' => $post_data['developers'] // 制定人 改成 手動輸入
						);
						// 已審核工作卡 上傳者要 on 上 初版(.doc)的作者名
						/*
						if($ori_author == ''){
							$insert_data['author']=$session['ssnUserName'];
						}else{
							$insert_data['author']=$ori_author;
						}
						*/
						$this->main_model->add_files($insert_data);
						
						// 新增動作寫入log
						$this->_action_insert_log($file_name, 'add', $session['ssnUserName'], '2');									
					// modify files
					}else{								
						$new_dir= $desired_dir."/".$file_name;
						 rename($file_tmp,$new_dir);
						 
						 $modify_data = array(
											'file_name' =>$file_name,
											//'editor' => $session['ssnUserName'],
											'editor' => $post_data['developers'],
											'table' => $post_data['table']											
						);
						 $this->main_model->modify_files($modify_data);
						// 修改動作寫入log
						$this->_action_insert_log($file_name, 'modify', $session['ssnUserName'], '2');				 
					}
				}
			}
		}		
		$url= $this->index_base_url.$post_data['table'];
		header("Location:$url" );
		return false;
	}	
	
	/**
	 * 刪除工作卡 功能
	 *
	 * @author      Easonwei
	 * @link        http://localhost/welcome/del_files
	 */			
	public function del_files()
	{		
		$get_data = $this->input->get();
		$session = $this->_get_session();
		// 檢查不合法刪除
		if(trim($session['ssnUserName']) !== $get_data['author']){
			// 非管理者
			if(!in_array(trim($session['ssnUserName']), $this->manage_group)){
				return;	
			}			
		}
		
		if($get_data['page'] == 'card_upload'){
			$del_dir = 'assets/del/card_upload';
			$desired_dir="assets/upload_data/card_upload";
		}elseif($get_data['page'] == 'review_card'){
			$del_dir = 'assets/del/review_card';
			$desired_dir="assets/upload_data/review_card";
		}
		
		if(is_dir($del_dir)==false){
			//mkdir("$del_dir", 0777);
			mkdir($del_dir, 0, true);
		}
		
		if (isset($get_data['del_name'])){		
			if (copy($desired_dir.'/'.$get_data['del_name'],$del_dir.'/'.$get_data['del_name'])) {
			  unlink($desired_dir.'/'.$get_data['del_name']);
			  
				$del_data = array(
								'file_name' =>$get_data['del_name'],
								'table' => $get_data['page'],
								'deletor' => $session['ssnUserName']
				);
				$this->main_model->del_files($del_data);
				// 刪除動作寫入log
				$this->_action_insert_log($get_data['del_name'], 'delete', $session['ssnUserName'], '3');
			}
		}
		
		// card_upload
		$url= $this->index_base_url.$get_data['page'];
		header("Location:$url" );
		return false;		
	}		
	
	/**
	 * 登入
	 *
	 * @author      Easonwei
	 * @link        http://localhost/welcome/login
	 */	
	public function login()
	{		
		$this->_return_index();
		$post_data = $this->input->post();
		if(isset($post_data['Username']))
		{	
			// 身分證小寫轉大寫
			if(preg_match("/[a-zA-Z][1-2]\d{8}/",$post_data['Username'])){
				$post_data['Username'] = strtoupper($post_data['Username']);
			}
			
			$data['account'] = $post_data['Username'];
			$data['passwd'] = $post_data['password'];	
	
			$data['ip'] = $this->input->ip_address();
			
			$this->load->library('plugin_something');
			
			$login_data = $this->plugin_something->check_user($data);

			if($login_data['status']){
				$newdata = array(
								   'ssnUserName'   => iconv("big5","UTF-8",$login_data['chName']),
								   'ssnLoginID' => $login_data['chUserID']
							   );
				$this->session->set_userdata($newdata);
				
				// 使用者登入 ip
				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip = $_SERVER['REMOTE_ADDR'];
				}	
				
				$query_data= array(
					'user_id' => trim($newdata['ssnLoginID']),
					'user_name' => trim($newdata['ssnUserName']),
					'ip' => $ip//$this->input->ip_address()
				);
				
				//login log
				$this->main_model->insert_log($query_data);
				
				$this->_return_index();
			}			
		}
		
		$data = array();
		$data['error_msg'] = '登入帳號密碼同訂餐系統';
		if(isset($login_data['status']) && $login_data['status']==false){
			$data['error_msg'] = "<span style='color:red;'>帳號或密碼錯誤</span>";
		}
		
		$data['base_url'] = base_url();
		$data['index_base_url'] = $this->index_base_url;
		$data['host_name'] = $this->host_name;
		$data['title'] = $this->host_name;
		$data['page_name'] = '工作卡上傳';
		
		$this->load->view('sky/head.php', $data);
		$this->load->view('sky/load_element.php', $data);	
		$this->load->view('sky/body.php');	
		$this->load->view('sky/login.php');				
		$this->load->view('sky/footer.php', $data);
		$this->load->view('sky/external.php', $data);
		
				
	}	
	
	/**
	 * 登出
	 *
	 * @author      Easonwei
	 * @link        http://localhost/welcome/logout
	 */		
	public function logout()
	{		
		$array_items = array('ssnUserName', 
							 'ssnLoginID',
							);
						 
		$this->session->unset_userdata($array_items);
		
		$this->_check_login();
	}
	
	/**
	 * 權限檢查
	 *
	 * @author      Easonwei
	 * @link        
	 */		
	private function _check_login()
	{		
		$_session = $this->session->all_userdata();

		if(!isset($_session['ssnLoginID'])){
			$url= $this->index_base_url."login";
			header("Location:$url" );
			return false;
		}
		return true; 
	}	
	
	/**
	 * 返回首頁
	 *
	 * @author      Easonwei
	 * @link       
	 */
	private function _return_index()
	{		
		$_session = $this->session->all_userdata();
		if(isset($_session['ssnLoginID']))
		{
			$url= $this->index_base_url;
			header("Location:$url" );
		}
		return; 
	}

	/**
	 * 取得 SESSION
	 *
	 * @author      Easonwei
	 * @link       
	 */
	private function _get_session()
	{		
		$_session = $this->session->all_userdata();
		return $_session; 
	}	
	
	/**
	 * 存取動作寫入log
	 *
	 * @author      Easonwei
	 * @link       
	 */
	private function _action_insert_log($file_name, $action, $user_name, $post_location)
	{		
		$operat_data = array(
						'file_name' =>$file_name,
						'action' => $action,
						'user' => $user_name,
						'post_location' => $post_location
		);
		$this->main_model->operat_log($operat_data);
		return;
	}	
	
	/**
	 * 組上傳過檔案之上傳人 下拉選單
	 *
	 * @author      Easonwei
	 * @link       
	 */
	private function _athor_option($all_users)
	{		
		// 組 上傳過檔案人的下拉選單
		$select_option = "<option value='' selected>--點擊查詢上傳者--</option>";
		foreach($all_users as $user){
			$select_option .= "<option value=".$user['author'].">".$user['author']."</option>";
		}
		
		return $select_option;
	}
}
