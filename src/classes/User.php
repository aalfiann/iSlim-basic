<?php
/**
 * This class is a part of reSlim project
 * @author M ABD AZIZ ALFIAN <github.com/aalfiann>
 *
 * Don't remove this class unless You know what to do
 *
 */
namespace classes;
use \classes\Auth as Auth;
use PDO;
	/**
     * A class for user management in reSlim
     *
     * @package    Core reSlim
     * @author     M ABD AZIZ ALFIAN <github.com/aalfiann>
     * @copyright  Copyright (c) 2016 M ABD AZIZ ALFIAN
     * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
     */
	class User {

		protected $db;

		var $Username,$Password,$Fullname,$Address,$Phone,$Email,$Aboutme,$Avatar,$Role,$Status,$Token,$NewPassword;

		function __construct($db=null) {
			if (!empty($db)) 
	        {
    	        $this->db = $db;
        	}
		}

		/**
		 * Inserting into database to register user
		 * @return result process in json encoded data
		 */
		private function doRegister(){
			
			$newusername = strtolower($this->Username);
			$newemail = strtolower($this->Email);
			$hash = Auth::HashPassword($newusername, $this->Password);
			
			try {
				$this->db->beginTransaction();
				$sql = "INSERT INTO user_data (Username,Password,Fullname,Address,Phone,Email,Aboutme,Avatar,RoleID,StatusID) 
					VALUES (:username,:password,:fullname,:address,:phone,:email,:aboutme,:avatar,:role,'1');";
					$stmt = $this->db->prepare($sql);
					$stmt->bindParam(':username', $newusername, PDO::PARAM_STR);
					$stmt->bindParam(':password', $hash, PDO::PARAM_STR);
					$stmt->bindParam(':fullname', $this->Fullname, PDO::PARAM_STR);
					$stmt->bindParam(':address', $this->Address, PDO::PARAM_STR);
					$stmt->bindParam(':phone', $this->Phone, PDO::PARAM_STR);
					$stmt->bindParam(':email', $newemail, PDO::PARAM_STR);
					$stmt->bindParam(':aboutme', $this->Aboutme, PDO::PARAM_STR);
					$stmt->bindParam(':avatar', $this->Avatar, PDO::PARAM_STR);
					$stmt->bindParam(':role', $this->Role, PDO::PARAM_STR);
					if ($stmt->execute()) {
						$data = [
							'status' => 'success',
							'code' => 'RS101',
							'message' => CustomHandlers::getreSlimMessage('RS101')
						];	
					} else {
						$data = [
							'status' => 'error',
							'code' => 'RS901',
							'message' => CustomHandlers::getreSlimMessage('RS901')
						];
					}
				$this->db->commit();
			} catch (PDOException $e) {
				$data = [
					'status' => 'error',
					'code' => $e->getCode(),
					'message' => $e->getMessage()
				];
				$this->db->rollBack();
			}
			return $data;
			$this->db = null;
		}

		/**
		 * Update user
		 * @return result process in json encoded data
		 */
		private function doUpdate(){
			
			$newusername = strtolower($this->Username);
			$newemail = strtolower($this->Email);

			try {
				$this->db->beginTransaction();
				$sql = "UPDATE user_data 
					SET Fullname=:fullname,Address=:address,Phone=:phone,Email=:email,Aboutme=:aboutme,Avatar=:avatar,
					RoleID=:role,StatusID=:status  
					WHERE Username=:username;";
					$stmt = $this->db->prepare($sql);
					$stmt->bindParam(':username', $newusername, PDO::PARAM_STR);
					$stmt->bindParam(':fullname', $this->Fullname, PDO::PARAM_STR);
					$stmt->bindParam(':address', $this->Address, PDO::PARAM_STR);
					$stmt->bindParam(':phone', $this->Phone, PDO::PARAM_STR);
					$stmt->bindParam(':email', $newemail, PDO::PARAM_STR);
					$stmt->bindParam(':aboutme', $this->Aboutme, PDO::PARAM_STR);
					$stmt->bindParam(':avatar', $this->Avatar, PDO::PARAM_STR);
					$stmt->bindParam(':role', $this->Role, PDO::PARAM_STR);
					$stmt->bindParam(':status', $this->Status, PDO::PARAM_STR);
					if ($stmt->execute()) {
						$data = [
							'status' => 'success',
							'code' => 'RS103',
							'message' => CustomHandlers::getreSlimMessage('RS103')
						];	
					} else {
						$data = [
							'status' => 'error',
							'code' => 'RS904',
							'message' => CustomHandlers::getreSlimMessage('RS904')
						];
					}
				$this->db->commit();
			} catch (PDOException $e) {
				$data = [
					'status' => 'error',
					'code' => $e->getCode(),
					'message' => $e->getMessage()
				];
				$this->db->rollBack();
			}
			return $data;
			$this->db = null;
		}

		/**
		 * Delete user
		 * @return result process in json encoded data
		 */
		private function doDelete(){
			$newusername = strtolower($this->Username);
			
			try {
				$this->db->beginTransaction();
				$sql = "DELETE FROM user_data WHERE Username=:username;";
					$stmt = $this->db->prepare($sql);
					$stmt->bindParam(':username', $newusername, PDO::PARAM_STR);
					if ($stmt->execute()) {
						$data = [
							'status' => 'success',
							'code' => 'RS104',
							'message' => CustomHandlers::getreSlimMessage('RS104')
						];	
					} else {
						$data = [
							'status' => 'error',
							'code' => 'RS905',
							'message' => CustomHandlers::getreSlimMessage('RS905')
						];
					}
				$this->db->commit();
			} catch (PDOException $e) {
				$data = [
					'status' => 'error',
					'code' => $e->getCode(),
					'message' => $e->getMessage()
				];
				$this->db->rollBack();
			}
			return $data;
			$this->db = null;
		}

		/**
		 * Change Password
		 * @return result process in json encoded data
		 */
		private function doChangePassword(){
			$newusername = strtolower($this->Username);
			$hash = Auth::HashPassword($newusername, $this->NewPassword);
			
			try {
				$this->db->beginTransaction();
				$sql = "UPDATE user_data a SET a.Password=:newpassword WHERE Username=:username;";
					$stmt = $this->db->prepare($sql);
					$stmt->bindParam(':username', $newusername, PDO::PARAM_STR);
					$stmt->bindParam(':newpassword', $hash, PDO::PARAM_STR);
					if ($stmt->execute()) {
						$data = [
							'status' => 'success',
							'code' => 'RS103',
							'message' => CustomHandlers::getreSlimMessage('RS103')
						];	
					} else {
						$data = [
							'status' => 'error',
							'code' => 'RS905',
							'message' => CustomHandlers::getreSlimMessage('RS905')
						];
					}
				$this->db->commit();
			} catch (PDOException $e) {
				$data = [
					'status' => 'error',
					'code' => $e->getCode(),
					'message' => $e->getMessage()
				];
				$this->db->rollBack();
			}
			return $data;
			$this->db = null;
		}

		/**
		 * Determine if user is already registered or not
		 * @return boolean true / false
		 */
		private function isRegistered(){
			$r = false;
			$sql = "SELECT a.Username
				FROM user_data a 
				WHERE a.Username = :username;";
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':username', $this->Username, PDO::PARAM_STR);
			if ($stmt->execute()) {	
            	if ($stmt->rowCount() > 0){
	                $r = true;
    	        }          	   	
			} 		
			return $r;
			$this->db = null;
		}

		/**
		 * Determine if user is active or not
		 * @return boolean true / false
		 */
		private function isActivated(){
			$r = false;
			$sql = "SELECT a.StatusID
				FROM user_data a 
				WHERE a.StatusID = '1' AND a.Username = :username;";
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':username', $this->Username, PDO::PARAM_STR);
			if ($stmt->execute()) {	
            	if ($stmt->rowCount() > 0){
	                $r = true;
    	        }          	   	
			} 		
			return $r;
			$this->db = null;
		}

		/**
		 * Determine if password is match
		 * @return boolean true / false
		 */
		private function isPasswordMatch(){
			$match = false;
			$sql = "SELECT a.Password
				FROM user_data a 
				WHERE a.Username = :username;";
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':username', $this->Username, PDO::PARAM_STR);
			if ($stmt->execute()){
				if ($stmt->rowCount() > 0){
					$single = $stmt->fetch();
					if (Auth::VerifyPassword($this->Username, $this->Password, $single['Password'])){
						$match = true;
					}
				}
			}
			return $match;
			$this->db = null;
		}

		/** 
		 * Get all data Role User
		 * @return result process in json encoded data
		 */
		public function showOptionRole() {
			if (Auth::ValidToken($this->db,$this->Token)){
				if (Auth::GetRoleID($this->db,$this->Token) == '1'){
					$sql = "SELECT a.RoleID,a.Role
					FROM user_role a
					ORDER BY a.Role ASC;";
				} else {
					$sql = "SELECT a.RoleID,a.Role
					FROM user_role a
					WHERE a.RoleID <> '1'
					ORDER BY a.Role ASC;";
				}
				
				$stmt = $this->db->prepare($sql);		
				$stmt->bindParam(':token', $this->Token, PDO::PARAM_STR);

				if ($stmt->execute()) {	
    	    	    if ($stmt->rowCount() > 0){
        	   		   	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$data = [
			   	            'result' => $results, 
    	    		        'status' => 'success', 
			           	    'code' => 'RS501',
        		        	'message' => CustomHandlers::getreSlimMessage('RS501')
						];
			        } else {
        			    $data = [
            		    	'status' => 'error',
		        		    'code' => 'RS601',
        		    	    'message' => CustomHandlers::getreSlimMessage('RS601')
						];
	    	        }          	   	
				} else {
					$data = [
    	    			'status' => 'error',
						'code' => 'RS202',
	        		    'message' => CustomHandlers::getreSlimMessage('RS202')
					];
				}
			} else {
				$data = [
	    			'status' => 'error',
					'code' => 'RS401',
        	    	'message' => CustomHandlers::getreSlimMessage('RS401')
				];
			}		
        
			return json_encode($data, JSON_PRETTY_PRINT);
	        $this->db= null;
		}

		/** 
		 * Get all data Status User
		 * @return result process in json encoded data
		 */
		public function showOptionStatus() {
			if (Auth::ValidToken($this->db,$this->Token)){
				$sql = "SELECT a.StatusID,a.Status
					FROM core_status a
					WHERE a.StatusID = '1' OR a.StatusID = '42'
					ORDER BY a.Status ASC";
				
				$stmt = $this->db->prepare($sql);		
				$stmt->bindParam(':token', $this->Token, PDO::PARAM_STR);

				if ($stmt->execute()) {	
    	    	    if ($stmt->rowCount() > 0){
        	   		   	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$data = [
			   	            'result' => $results, 
    	    		        'status' => 'success', 
			           	    'code' => 'RS501',
        		        	'message' => CustomHandlers::getreSlimMessage('RS501')
						];
			        } else {
        			    $data = [
            		    	'status' => 'error',
		        		    'code' => 'RS601',
        		    	    'message' => CustomHandlers::getreSlimMessage('RS601')
						];
	    	        }          	   	
				} else {
					$data = [
    	    			'status' => 'error',
						'code' => 'RS202',
	        		    'message' => CustomHandlers::getreSlimMessage('RS202')
					];
				}
			} else {
				$data = [
	    			'status' => 'error',
					'code' => 'RS401',
        	    	'message' => CustomHandlers::getreSlimMessage('RS401')
				];
			}		
        
			return json_encode($data, JSON_PRETTY_PRINT);
	        $this->db= null;
		}
	
		/** 
		 * Get all data user
		 * @return result process in json encoded data
		 */
		public function showAll() {
			if (Auth::ValidToken($this->db,$this->Token)){
				if (Auth::GetRoleID($this->db,$this->Token) == '1'){
					$sql = "SELECT a.Username, a.Fullname, a.Address, a.Phone, a.Email, a.Aboutme,a.Avatar, b.Role , c.Status,
							a.Created_at, a.Updated_at
						FROM user_data a 
						INNER JOIN user_role b ON a.RoleID = b.RoleID
						INNER JOIN core_status c ON a.StatusID = c.StatusID
						ORDER BY a.Fullname ASC;";
				} else {
					$sql = "SELECT a.Username, a.Fullname, a.Address, a.Phone, a.Email, a.Aboutme,a.Avatar, b.Role , c.Status,
							a.Created_at, a.Updated_at
						FROM user_data a 
						INNER JOIN user_role b ON a.RoleID = b.RoleID
						INNER JOIN core_status c ON a.StatusID = c.StatusID
						WHERE a.RoleID <> '1' AND a.RoleID <> '2'
						UNION
						SELECT b.Username, b.Fullname, b.Address, b.Phone, b.Email, b.Aboutme,b.Avatar, c.Role , d.Status,
							b.Created_at, b.Updated_at
						FROM user_auth a 
						INNER JOIN user_data b ON a.Username = b.Username
						INNER JOIN user_role c ON b.RoleID = c.RoleID
						INNER JOIN core_status d ON b.StatusID = d.StatusID
						WHERE a.RS_Token=:token
						ORDER BY Fullname ASC;";
				}
				
				$stmt = $this->db->prepare($sql);		
				$stmt->bindParam(':token', $this->Token, PDO::PARAM_STR);

				if ($stmt->execute()) {	
    	    	    if ($stmt->rowCount() > 0){
        	   		   	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$data = [
			   	            'result' => $results, 
    	    		        'status' => 'success', 
			           	    'code' => 'RS501',
        		        	'message' => CustomHandlers::getreSlimMessage('RS501')
						];
			        } else {
        			    $data = [
            		    	'status' => 'error',
		        		    'code' => 'RS601',
        		    	    'message' => CustomHandlers::getreSlimMessage('RS601')
						];
	    	        }          	   	
				} else {
					$data = [
    	    			'status' => 'error',
						'code' => 'RS202',
	        		    'message' => CustomHandlers::getreSlimMessage('RS202')
					];
				}
			} else {
				$data = [
	    			'status' => 'error',
					'code' => 'RS401',
        	    	'message' => CustomHandlers::getreSlimMessage('RS401')
				];
			}		
        
			return json_encode($data, JSON_PRETTY_PRINT);
	        $this->db= null;
		}

		/** 
		 * Get data single user
		 * @return result process in json encoded data
		 */
		public function showUser() {
			$sql = "SELECT a.Username, a.Fullname, a.Address, a.Phone, a.Email, a.Aboutme,a.Avatar, b.Role , c.Status,
						a.Created_at, a.Updated_at
					FROM user_data a 
					INNER JOIN user_role b ON a.RoleID = b.RoleID
					INNER JOIN core_status c ON a.StatusID = c.StatusID
					WHERE a.Username = :username;";
				
			$stmt = $this->db->prepare($sql);		
			$stmt->bindParam(':username', $this->Username, PDO::PARAM_STR);

			if ($stmt->execute()) {	
    	    	if ($stmt->rowCount() > 0){
        		   	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$data = [
		   	            'result' => $results, 
	    		        'status' => 'success', 
			           	'code' => 'RS501',
        		        'message' => CustomHandlers::getreSlimMessage('RS501')
					];
			    } else {
        			$data = [
            		   	'status' => 'error',
		        	    'code' => 'RS601',
        		        'message' => CustomHandlers::getreSlimMessage('RS601')
					];
	    	    }          	   	
			} else {
				$data = [
    	    		'status' => 'error',
					'code' => 'RS202',
	        	    'message' => CustomHandlers::getreSlimMessage('RS202')
				];
			}		
        
			return json_encode($data, JSON_PRETTY_PRINT);
	        $this->db= null;
		}

		/** 
		 * Regiter new user
		 * @return result process in json encoded data
		 */
		public function register(){
			if ( preg_match('/[A-Za-z0-9]+/',$this->Username) == false ){
				$data = [
					'status' => 'error',
					'code' => 'RS804',
					'message' => CustomHandlers::getreSlimMessage('RS804')
				];
			} else {
				if ($this->isRegistered() == false){
					$data = $this->doRegister();
				} else {
					$data = [
						'status' => 'error',
						'code' => 'RS902',
						'message' => CustomHandlers::getreSlimMessage('RS902')
					];
				}
			}
			
			return json_encode($data,JSON_PRETTY_PRINT);
		}

		/** 
		 * Login user
		 * @return result process in json encoded data
		 */
		public function login(){
			if ( preg_match('/[A-Za-z0-9]+/',$this->Username) == false ){
				$data = [
					'status' => 'error',
					'code' => 'RS804',
					'message' => CustomHandlers::getreSlimMessage('RS804')
				];
			} else {
				if ($this->isRegistered()){
					if ($this->isActivated()) {
						if ($this->isPasswordMatch()){
							$data = Auth::GenerateToken($this->db,$this->Username);
						} else {
							$data = [
								'status' => 'error',
								'code' => 'RS903',
								'message' => CustomHandlers::getreSlimMessage('RS903')
							];
						}
					} else {
						$data = [
							'status' => 'error',
							'code' => 'RS906',
							'message' => CustomHandlers::getreSlimMessage('RS906')
						];
					}
				} else {
					$data = [
						'status' => 'error',
						'code' => 'RS902',
						'message' => CustomHandlers::getreSlimMessage('RS902')
					];
				}
			}
		
			return json_encode($data, JSON_PRETTY_PRINT);
		}

		/** 
		 * Logout user
		 * @return result process in json encoded data
		 */
		public function logout(){
			$data = Auth::ClearToken($this->db,$this->Username,$this->Token);
			return json_encode($data, JSON_PRETTY_PRINT);
		}

		/** 
		 * Update user
		 * @return result process in json encoded data
		 */
		public function update(){
			if (Auth::ValidToken($this->db,$this->Token)){
				$data = $this->doUpdate();
			} else {
				$data = [
	    			'status' => 'error',
					'code' => 'RS401',
        	    	'message' => CustomHandlers::getreSlimMessage('RS401')
				];
			}
			return json_encode($data, JSON_PRETTY_PRINT);
		}

		/** 
		 * Delete user
		 * @return result process in json encoded data
		 */
		public function delete(){
			if (Auth::ValidToken($this->db,$this->Token)){
				if ($this->isRegistered()){
					$data = $this->doDelete();
				} else {
					$data = [
	    				'status' => 'error',
						'code' => 'RS902',
	        	    	'message' => CustomHandlers::getreSlimMessage('RS902')
					];
				}
			} else {
				$data = [
	    			'status' => 'error',
					'code' => 'RS401',
        	    	'message' => CustomHandlers::getreSlimMessage('RS401')
				];
			}
			return json_encode($data, JSON_PRETTY_PRINT);
		}

		/** 
		 * Change Password
		 * @return result process in json encoded data
		 */
		public function changePassword(){
			if (Auth::ValidToken($this->db,$this->Token)){
				if ($this->isRegistered()){
					$data = $this->doChangePassword();
					Auth::ClearUserToken($this->db,$this->Username);
				} else {
					$data = [
	    				'status' => 'error',
						'code' => 'RS907',
	        	    	'message' => CustomHandlers::getreSlimMessage('RS907')
					];
				}
			} else {
				$data = [
	    			'status' => 'error',
					'code' => 'RS401',
        	    	'message' => CustomHandlers::getreSlimMessage('RS401')
				];
			}
			return json_encode($data, JSON_PRETTY_PRINT);
		}

	}