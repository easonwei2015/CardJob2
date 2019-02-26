<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plugin_something {

        protected $CI;

        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CI =& get_instance();
        }

        public function check_user($data)
        {
			$conn = $this->_odbc_connect();
			
			//登入檢查
			$sql = "SELECT chName,chUserID,count(1) as count
					  FROM [SIGIN].[dbo].GenMemberProfileTbl
						  where chUserID = '".$data['account']."'
							  and chPwd= '".$data['passwd']."'							  
						group by chName,chUserID";
						

			$result = odbc_exec($conn, $sql);

			$data = odbc_fetch_array($result);

			if(!empty($data['chName'])){
				$data['status'] = true;
			}else{
				$data['status'] = false;
				
			}
			
			
			odbc_close($conn);	
			return $data;			
        }
		
		private function _odbc_connect(){
			// odbc 連線方式
			$server = "10.2.251.251";
			$database="SIGIN";
			$user="guid";
			$password="gpwd";
			$conn = odbc_connect("Driver={SQL Server};Server=$server;Database=$database;", $user, $password);		
			return $conn;
		}
		
		

}

?>