<?php
define('USER_LOGIN_FAILED_ACTIVATION', 2);
define('USER_LOGIN_SUCCEEDED', 1);
define('USER_LOGIN_FAILED', 0);
define('USER_ACCOUNT_BLOCKED', -1535);
define('USER_LOGIN_FAILED_BLOCKED', -2);

class User {
	var $id;
	var $sex;
	var $name;
	var $email;
	var $password;
	var $sess;
	var $tableName;
	var $loginAttempt;

	function User() {
		$numArgs = func_num_args();
		$args = func_get_args();
		$this->setLoginAttempt(0);
		$this->setId(-1);
		if ($numArgs == 5) {
			$this->setSex($args[0]);
			$this->setName($args[1]);
			$this->setEmail($args[2]);
			$this->setPassword($args[3]);
			$this->setSess($args[4]);
		} elseif ($numArgs == 1) {
			$this->setSex(0);
			$this->setName('');
			$this->setEmail('');
			$this->setPassword('');
			$this->setSess($args[0]);
		}
	}

	function setSess($sess) {
		$this->sess = $sess;
	}

	function setPassword($password) {
		$this->password = $password;
	}

	function setSex($sex) {
		$this->sex = intval($sex);
	}

	function setName($name) {
		$this->name = $name;
	}

	function setEmail($email) {
		$this->email = $email;
	}

	function setId($id) {
		$this->id = intval($id);
	}

	function setLoginAttempt($attempt) {
		$this->loginAttempt = $attempt;
	}

	function activate($code) {
		global $db, $escape_mysql_string;

		$db->sql_query("UPDATE {$this->tableName} SET activationCode=NULL WHERE activationCode='".$escape_mysql_string($code)."' AND email='".$escape_mysql_string($this->email)."'");
		if ($db->sql_affectedrows() > 0) return true;
		else return false;
	}

	function unblock($code) {
		global $db, $escape_mysql_string;

		$unblockCode = $escape_mysql_string($code);
		$db->sql_query("UPDATE {$this->tableName} SET loginAttempt=0, unblockCode=NULL WHERE unblockCode='$unblockCode' AND email='".$escape_mysql_string($this->email)."'");
		if ($db->sql_affectedrows() > 0) return true;
		else return false;
	}

	function del() {
		global $db;

		if ($this->id != -1) {
			$db->sql_query("DELETE FROM {$this->tableName} WHERE id={$this->id}");
			if ($db->sql_affectedrows() > 0) return true;
			else false;
		} else return false;
	}
	//function recover password
	function recover($code, $newPass) {
		global $escape_mysql_string, $db, $adminmail;
		//password auto create
		//$newPass = uniqid('');
		$db->sql_query("UPDATE {$this->tableName} SET recoverCode=NULL, pass='".md5($newPass)."' WHERE email='".$escape_mysql_string($this->email)."' AND recoverCode='".$escape_mysql_string($code)."'");
		if ($db->sql_affectedrows() > 0)
		{
			$recoverMsg = "Mật khẩu mới của bạn đã được tạo thành công!";
			sendmail(_USER_RECOVER_SUBJECT, $this->email, $adminmail, $recoverMsg);
			return $newPass;
			$db->sql_query("UPDATE {$prefix}_user SET recoverCode=NULL WHERE email='".$escape_mysql_string($this->email)."'");
		}
		else
		{
			return false;
		}
	}
	
	function newRecover($url) {
		global $db, $escape_mysql_string, $module_name, $adminmail;
		
		$recoverCode = md5(uniqid(rand(), true));
		$db->sql_query("UPDATE {$this->tableName} SET recoverCode='$recoverCode', recoverCodeTime=NOW() WHERE email='".$escape_mysql_string($this->email)."'");
		if ($db->sql_affectedrows() > 0) {
			$recoverURL = Common::constructURL($url, "?f={$module_name}&do=recover&email={$this->email}&code=$recoverCode");
			$recoverMsg = _USER_GREETING.',<br />'._USER_RECOVER_EXPLAIN.'<br />'._USER_CLICK_TO_RECOVER.": <a href=\"$recoverURL\" target=\"_blank\">$recoverURL</a><br />";
			$recoverMsg .= _USER_THIS_LINK_IS_VALID_FOR." $recoverPeriod "._USER_HOUR."<br />"._USER_FOOTER;
			sendmail(_USER_RECOVER_SUBJECT, $this->email, $adminmail, $recoverMsg);
			return true;
		} else return false;
	}

	function login($email, $password, $maxLoginAttempt, $url) {
		global $db, $module_name, $adminmail;

		$password = md5($password);

		if ($this->loginAttempt >= $maxLoginAttempt) return USER_ACCOUNT_BLOCKED;
		else {
			$result= $db->sql_query("SELECT activationCode FROM {$this->tableName} WHERE activationCode IS NOT NULL AND id={$this->id}");
			if ($db->sql_numrows($result) > 0){
				return USER_LOGIN_FAILED_ACTIVATION;
			}
			else{
				$result=$db->sql_query("SELECT actives FROM {$this->tableName} WHERE actives=0 AND id={$this->id}");
				if ($db->sql_numrows($result) > 0){
					return USER_ACCOUNT_BLOCKED;
				}
				else{
					if ((($email == $this->name) || ($email == $this->email)) && ($password == $this->password)) {
						
						$db->sql_query("UPDATE {$this->tableName} SET loginAttempt=0 WHERE id={$this->id}");
						$this->setLoginAttempt(0);
						$_SESSION[$this->sess] = "$email;$password";
						return USER_LOGIN_SUCCEEDED;
						
					} else {
						
						$db->sql_query("UPDATE {$this->tableName} SET loginAttempt=loginAttempt+1 WHERE id={$this->id}");
						$this->setLoginAttempt($this->loginAttempt + 1);
						if ($this->loginAttempt == $maxLoginAttempt) {
							$unblockCode = md5(uniqid(rand(), true));
							$db->sql_query("UPDATE {$this->tableName} SET unblockCode='$unblockCode' WHERE id={$this->id}");
							$recoverURL = Common::constructURL($url, "?f={$module_name}&do=recover");
							$unblockURL = Common::constructURL($url, "?f={$module_name}&do=unblock&email=$email&code=$unblockCode");
							die($recoverURL."<br />".$unblockURL);
							$blockedMsg = _USER_GREETING.",<br />"._USER_BLOCKED_EXPLAIN.": <a href=\"$recoverURL\" target=\"_blank\">$recoverURL</a>.<br />";
							$blockedMsg .= _USER_CLICK_TO_UNBLOCK.": <a href=\"$unblockURL\" target=\"_blank\">$unblockURL</a>.<br />";
							sendmail(_USER_ACCOUNT_BLOCKED_SUBJECT, $email, $adminmail, $blockedMsg);
							return USER_LOGIN_FAILED_BLOCKED;
						}
						return USER_LOGIN_FAILED;
					}
				}
			}
		}
	}
}
?>