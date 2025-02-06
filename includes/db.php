<?php

if ((!defined('CMS_SYSTEM')) && (!defined('CMS_ADMIN'))) die();

function get_microtime() {
	list($usec, $sec) = explode(" ", microtime());
	return ($usec + $sec);
}

class sql_db
{

	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $time = 0;
	var $query_ids = array();

	//
	// Constructor
	//
	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true) {
		$stime = get_microtime();
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;

		if($this->persistency)
		{
			$this->db_connect_id = @mysql_pconnect($this->server, $this->user, $this->password);
		}
		else
		{
			$this->db_connect_id = @mysql_connect($this->server, $this->user, $this->password);
			
		}
		//mysq_set_charset($this->db_connect_id, 'UTF8');
		mysql_set_charset("UTF8", $this->db_connect_id);
		if($this->db_connect_id)
		{
			if($database != "")
			{
				$this->dbname = $database;
				$dbselect = @mysql_select_db($this->dbname);
				
				if(!$dbselect)
				{
					@mysql_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}
			$this->time += (get_microtime()-$stime);
			return $this->db_connect_id;
		}
		else
		{
			return false;
		}
	}

	//
	// Other base methods
	//
	function sql_close() {
		if($this->db_connect_id) {
			$numid = count($this->query_ids);
			for ($i=0; $i<$numid; $i++) {
				if (isset($this->query_ids[$i])) { @mysql_free_result($this->query_ids[$i]); }
			}
			if (!$this->persistency) {
				$result = @mysql_close($this->db_connect_id);
				$this->db_connect_id = NULL;
				return $result;
			}
			return false;
		}
		else
		{
			return false;
		}
	}

	//
	// Base query method
	//
	function sql_query($query = "", $transaction = FALSE) {
		$stime = get_microtime();
		// Remove any pre-existing queries
		unset($this->query_result);
		if (!empty($query)) {
			$this->query_result = @mysql_query($query, $this->db_connect_id);
			$this->num_queries++;
		}
		if ($this->query_result) {
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);
			$this->time += (get_microtime()-$stime);
			$this->query_ids[] = $this->query_result;
			return $this->query_result;
		} elseif (defined("DEBUG") && (DEBUG == 1)) {
			$thisError = $this->sql_error();
			echo "<p>mySQL Error: {$thisError['message']} (Code: {$thisError['code']})</p>";
		}
	}

	//
	// Other query methods
	//
	function sql_numrows($query_id = 0) {
		$stime = get_microtime();
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$result = @mysql_num_rows($query_id);
			$this->time += (get_microtime()-$stime);
			return $result;
		}
		else {
			$this->time += (get_microtime()-$stime);
			return false;
		}
	}

	function sql_affectedrows() {
		$stime = get_microtime();
		if($this->db_connect_id) {
			$result = @mysql_affected_rows($this->db_connect_id);
			$this->time += (get_microtime()-$stime);
			return $result;
		}
		else {
			return false;
		}
	}

	function sql_numfields($query_id = 0) {
		$stime = get_microtime();
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$result = @mysql_num_fields($query_id);
			$this->time += (get_microtime()-$stime);
			return $result;
		}
		else {
			$this->time += (get_microtime()-$stime);
			return false;
		}
	}

	function sql_fieldname($offset, $query_id = 0) {
		$stime = get_microtime();
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$result = @mysql_field_name($query_id, $offset);
			$this->time += (get_microtime()-$stime);
			return $result;
		}
		else {
			$this->time += (get_microtime()-$stime);
			return false;
		}
	}

	function sql_fieldtype($offset, $query_id = 0) {
		$stime = get_microtime();
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$result = @mysql_field_type($query_id, $offset);
			$this->time += (get_microtime()-$stime);
			return $result;
		}
		else {
			return false;
		}
	}

	function sql_fetchrow($query_id = 0) {
		$stime = get_microtime();
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$this->row[$query_id] = @mysql_fetch_array($query_id);
			$this->time += (get_microtime()-$stime);
			return $this->row[$query_id];
		}
		else {
			return false;
		}
	}

	function sql_fetchrowset($query_id = 0) {
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$stime = get_microtime();
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while($this->rowset[$query_id] = @mysql_fetch_array($query_id)) {
				$result[] = $this->rowset[$query_id];
			}
			$this->time += (get_microtime()-$stime);
			return $result;
		} else 	{
			return false;
		}
	}

	function sql_fetchfield($field, $rownum = -1, $query_id = 0) {
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			if($rownum > -1) {
				$result = @mysql_result($query_id, $rownum, $field);
			} else {
				if(empty($this->row[$query_id]) && empty($this->rowset[$query_id])) {
					if($this->sql_fetchrow()) {
						$result = $this->row[$query_id][$field];
					}
				} else {
					if($this->rowset[$query_id]) {
						$result = $this->rowset[$query_id][$field];
					} else if($this->row[$query_id]) {
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		} else {
			return false;
		}
	}

	function sql_rowseek($rownum, $query_id = 0) {
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		} else {
			return false;
		}
	}

	function sql_nextid() {
		if($this->db_connect_id) {
			$result = @mysql_insert_id($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_freeresult($query_id = 0) {
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if ( $query_id ) {
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);
			@mysql_free_result($query_id);
			$numid = count($this->query_ids);
			for ($i=0; $i < $numid; $i++) {
				if ($this->query_ids[$i] == $query_id) {
					unset($this->query_ids[$i]);
					return true;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	function sql_error($query_id = 0) {
		$result["message"] = @mysql_error($this->db_connect_id);
		$result["code"] = @mysql_errno($this->db_connect_id);
		return $result;
	}

} // class sql_db

?>