<?php
/**
* @version $Id: database.php 4546 2006-08-16 23:41:44Z facedancer $
* @package Joomla
* @subpackage Database
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( 'INSIDE' ) or die( 'Restricted access' );

/**
* Database connector class
* @subpackage Database
* @package Joomla
*/
class og_database {
	/** @var string Internal variable to hold the query sql */
	var $_sql			= '';
	/** @var int Internal variable to hold the database error number */
	var $_errorNum		= 0;
	/** @var string Internal variable to hold the database error message */
	var $_errorMsg		= '';
	/** @var string Internal variable to hold the prefix used on all database tables */
	var $_table_prefix	= '';
	/** @var Internal variable to hold the connector resource */
	var $_resource		= '';
	/** @var Internal variable to hold the last query cursor */
	var $_cursor		= null;
	/** @var boolean Debug option */
	var $_debug			= 0;
	/** @var int The limit for the query */
	var $_limit			= 0;
	/** @var int The for offset for the limit */
	var $_offset		= 0;
	/** @var int A counter for the number of queries performed by the object instance */
	var $_ticker		= 0;
	/** @var array A log of queries */
	var $_log			= null;
	/** @var string The null/zero date string */
	var $_nullDate		= '0000-00-00 00:00:00';
	/** @var string Quote for named objects */
	var $_nameQuote		= '`';

	/**
	* Database object constructor
	* @param string Database host
	* @param string Database user name
	* @param string Database user password
	* @param string Database name
	* @param string Common prefix for all tables
	* @param boolean If true and there is an error, go offline
	*/
	function og_database( $host='localhost', $user, $pass, $db='', $table_prefix='', $goOffline=true ) {
		// perform a number of fatality checks, then die gracefully
		if (!function_exists( 'mysql_connect' )) {
			$mosSystemError = 1;
			if ($goOffline) {
				$basePath = dirname( __FILE__ );
				//include $basePath . '/../configuration.php';
				//include $basePath . '/../offline.php';
				exit();
			}
		}
		if (phpversion() < '4.2.0') {
			if (!($this->_resource = @mysql_connect( $host, $user, $pass ))) {
				$mosSystemError = 2;
				if ($goOffline) {
					$basePath = dirname( __FILE__ );
					//include $basePath . '/../configuration.php';
					//include $basePath . '/../offline.php';
					exit();
				}
			}
		} else {		
			if (!($this->_resource = @mysql_connect( $host, $user, $pass, true ))) {
				$mosSystemError = 2;
				if ($goOffline) {
					$basePath = dirname( __FILE__ );
					//include $basePath . '/../configuration.php';
					//include $basePath . '/../offline.php';
					exit();
				}
			}
		}
		if ($db != '' && !mysql_select_db( $db, $this->_resource )) {
			$mosSystemError = 3;
			if ($goOffline) {
				$basePath = dirname( __FILE__ );
				//include $basePath . '/../configuration.php';
				//include $basePath . '/../offline.php';
				exit();
			}
		}
		$this->_table_prefix = $table_prefix;
        @mysql_query("SET NAMES 'utf8'", $this->_resource);
		$this->_ticker = 0;
		$this->_log = array();
	}
	/**
	 * @param int
	 */
	function debug( $level ) {
		$this->_debug = intval( $level );
	}
	/**
	 * @return int The error number for the most recent query
	 */
	function getErrorNum() {
		return $this->_errorNum;
	}
	/**
	* @return string The error message for the most recent query
	*/
	function getErrorMsg() {
		return str_replace( array( "\n", "'" ), array( '\n', "\'" ), $this->_errorMsg );
	}
	/**
	* Get a database escaped string
	* @return string
	*/
	function getEscaped( $text ) {
		/*
		* Use the appropriate escape string depending upon which version of php
		* you are running
		*/
		if (version_compare(phpversion(), '4.3.0', '<')) {
			$string = mysql_escape_string($text);
		} else 	{
			$string = mysql_real_escape_string($text);
		}
		
		return $string;
	}
	/**
	* Get a quoted database escaped string
	* @return string
	*/
	function Quote( $text ) {
		return '\'' . $this->getEscaped( $text ) . '\'';
	}
	/**
	 * Quote an identifier name (field, table, etc)
	 * @param string The name
	 * @return string The quoted name
	 */
	function NameQuote( $s ) {
		$q = $this->_nameQuote;
		if (strlen( $q ) == 1) {
			return $q . $s . $q;
		} else {
			return $q{0} . $s . $q{1};
		}
	}
	/**
	 * @return string The database prefix
	 */
	function getPrefix() {
		return $this->_table_prefix;
	}
	/**
	 * @return string Quoted null/zero date string
	 */
	function getNullDate() {
		return $this->_nullDate;
	}
	/**
	* Sets the SQL query string for later execution.
	*
	* This function replaces a string identifier <var>$prefix</var> with the
	* string held is the <var>_table_prefix</var> class variable.
	*
	* @param string The SQL query
	* @param string The offset to start selection
	* @param string The number of results to return
	* @param string The common table prefix
	*/
	function setQuery( $sql, $offset = 0, $limit = 0, $prefix='#__' ) {
		$this->_sql = $this->replacePrefix( $sql, $prefix );
		$this->_limit = intval( $limit );
		$this->_offset = intval( $offset );
	}

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @param string The SQL query
	 * @param string The common table prefix
	 * @author thede, David McKinnis
	 */
	function replacePrefix( $sql, $prefix='#__' ) {
		$sql = trim( $sql );

		$escaped = false;
		$quoteChar = '';

		$n = strlen( $sql );

		$startPos = 0;
		$literal = '';
		while ($startPos < $n) {
			$ip = strpos($sql, $prefix, $startPos);
			if ($ip === false) {
				break;
			}

			$j = strpos( $sql, "'", $startPos );
			$k = strpos( $sql, '"', $startPos );
			if (($k !== FALSE) && (($k < $j) || ($j === FALSE))) {
				$quoteChar	= '"';
				$j			= $k;
			} else {
				$quoteChar	= "'";
			}

			if ($j === false) {
				$j = $n;
			}

			$literal .= str_replace( $prefix, $this->_table_prefix, substr( $sql, $startPos, $j - $startPos ) );
			$startPos = $j;

			$j = $startPos + 1;

			if ($j >= $n) {
				break;
			}

			// quote comes first, find end of quote
			while (TRUE) {
				$k = strpos( $sql, $quoteChar, $j );
				$escaped = false;
				if ($k === false) {
					break;
				}
				$l = $k - 1;
				while ($l >= 0 && $sql{$l} == '\\') {
					$l--;
					$escaped = !$escaped;
				}
				if ($escaped) {
					$j	= $k+1;
					continue;
				}
				break;
			}
			if ($k === FALSE) {
				// error in the query - no end quote; ignore it
				break;
			}
			$literal .= substr( $sql, $startPos, $k - $startPos + 1 );
			$startPos = $k+1;
		}
		if ($startPos < $n) {
			$literal .= substr( $sql, $startPos, $n - $startPos );
		}
		return $literal;
	}
	/**
	* @return string The current value of the internal SQL vairable
	*/
	function getQuery() {
		return "<pre>" . htmlspecialchars( $this->_sql ) . "</pre>";
	}
	/**
	* Execute the query
	* @return mixed A database resource if successful, FALSE if not.
	*/
	function query() {
		global $mosConfig_debug;
		if ($this->_limit > 0 || $this->_offset > 0) {
			$this->_sql .= "\nLIMIT $this->_offset, $this->_limit";
		}		
		if ($this->_debug) {
			$this->_ticker++;
	  		$this->_log[] = $this->_sql;
		}
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		$this->_cursor = mysql_query( $this->_sql, $this->_resource );
		if (!$this->_cursor) {
			$this->_errorNum = mysql_errno( $this->_resource );
			$this->_errorMsg = mysql_error( $this->_resource )." SQL=$this->_sql";
			if ($this->_debug) {
				trigger_error( mysql_error( $this->_resource ), E_USER_NOTICE );
				//echo "<pre>" . $this->_sql . "</pre>\n";
				if (function_exists( 'debug_backtrace' )) {
					foreach( debug_backtrace() as $back) {
						if (@$back['file']) {
							echo '<br />'.$back['file'].':'.$back['line'];
						}
					}
				}
			}
			return false;
		}
		return $this->_cursor;
	}

	/**
	 * @return int The number of affected rows in the previous operation
	 */
	function getAffectedRows() {
		return mysql_affected_rows( $this->_resource );
	}

	function query_batch( $abort_on_error=true, $p_transaction_safe = false) {
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		if ($p_transaction_safe) {
			$si = mysql_get_server_info( $this->_resource );
			preg_match_all( "/(\d+)\.(\d+)\.(\d+)/i", $si, $m );
			if ($m[1] >= 4) {
				$this->_sql = 'START TRANSACTION;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 19) {
				$this->_sql = 'BEGIN WORK;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 17) {
				$this->_sql = 'BEGIN;' . $this->_sql . '; COMMIT;';
			}
		}
		$query_split = preg_split ("/[;]+/", $this->_sql);
		$error = 0;
		foreach ($query_split as $command_line) {
			$command_line = trim( $command_line );
			if ($command_line != '') {
				$this->_cursor = mysql_query( $command_line, $this->_resource );
				if (!$this->_cursor) {
					$error = 1;
					$this->_errorNum .= mysql_errno( $this->_resource ) . ' ';
					$this->_errorMsg .= mysql_error( $this->_resource )." SQL=$command_line <br />";
					if ($abort_on_error) {
						return $this->_cursor;
					}
				}
			}
		}
		return $error ? false : true;
	}

	/**
	* Diagnostic function
	*/
	function explain() {
		$temp = $this->_sql;
		$this->_sql = "EXPLAIN $this->_sql";
		$this->query();

		if (!($cur = $this->query())) {
			return null;
		}
		$first = true;

		$buf = "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" bgcolor=\"#000000\" align=\"center\">";
		$buf .= $this->getQuery();
		while ($row = mysql_fetch_assoc( $cur )) {
			if ($first) {
				$buf .= "<tr>";
				foreach ($row as $k=>$v) {
					$buf .= "<th bgcolor=\"#ffffff\">$k</th>";
				}
				$buf .= "</tr>";
				$first = false;
			}
			$buf .= "<tr>";
			foreach ($row as $k=>$v) {
				$buf .= "<td bgcolor=\"#ffffff\">$v</td>";
			}
			$buf .= "</tr>";
		}
		$buf .= "</table><br />&nbsp;";
		mysql_free_result( $cur );

		$this->_sql = $temp;

		return "<div style=\"background-color:#FFFFCC\" align=\"left\">$buf</div>";
	}
	/**
	* @return int The number of rows returned from the most recent query.
	*/
	function getNumRows( $cur=null ) {
		return mysql_num_rows( $cur ? $cur : $this->_cursor );
	}

	/**
	* This method loads the first field of the first row returned by the query.
	*
	* @return The value returned in the query or null if the query failed.
	*/
	function loadResult() {
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = mysql_fetch_row( $cur )) {
			$ret = $row[0];
		}
		mysql_free_result( $cur );
		return $ret;
	}
	/**
	* Load an array of single field results into an array
	*/
	function loadResultArray($numinarray = 0) {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysql_fetch_row( $cur )) {
			$array[] = $row[$numinarray];
		}
		mysql_free_result( $cur );
		return $array;
	}
	/**
	* Load a assoc list of database rows
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	*/
	function loadAssocList( $key='' ) {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysql_fetch_assoc( $cur )) {
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysql_free_result( $cur );
		return $array;
	}
	/**
	* This global function loads the first row of a query into an object
	*
	* If an object is passed to this function, the returned row is bound to the existing elements of <var>object</var>.
	* If <var>object</var> has a value of null, then all of the returned query fields returned in the object.
	* @param string The SQL query
	* @param object The address of variable
	*/
	function loadObject( &$object ) {
		if ($object != null) {
			if (!($cur = $this->query())) {
				return false;
			}
			if ($array = mysql_fetch_assoc( $cur )) {
				mysql_free_result( $cur );
				//mosBindArrayToObject( $array, $object, null, null, false );
				return true;
			} else {
				return false;
			}
		} else {
			if ($cur = $this->query()) {
				if ($object = mysql_fetch_object( $cur )) {
					mysql_free_result( $cur );
					return true;
				} else {
					$object = null;
					return false;
				}
			} else {
				return false;
			}
		}
	}
	/**
	* Load a list of database objects
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	* If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*/
	function loadObjectList( $key='' ) {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysql_fetch_object( $cur )) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysql_free_result( $cur );
		return $array;
	}
	/**
	* @return The first row of the query.
	*/
	function loadRow() {
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = mysql_fetch_row( $cur )) {
			$ret = $row;
		}
		mysql_free_result( $cur );
		return $ret;
	}
	/**
	* Load a list of database rows (numeric column indexing)
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	* If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*/
	function loadRowList( $key='' ) {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysql_fetch_row( $cur )) {
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysql_free_result( $cur );
		return $array;
	}
	/**
	* Document::db_insertObject()
	*
	* { Description }
	*
	* @param [type] $keyName
	* @param [type] $verbose
	*/
	function insertObject( $table, &$object, $keyName = NULL, $verbose=false ) {
		$fmtsql = "INSERT INTO $table ( %s ) VALUES ( %s ) ";
		$fields = array();
		foreach (get_object_vars( $object ) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			$fields[] = $this->NameQuote( $k );
			$values[] = $this->Quote( $v );
		}
		$this->setQuery( sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) ) );
		($verbose) && print "$sql<br />\n";
		if (!$this->query()) {
			return false;
		}
		$id = mysql_insert_id( $this->_resource );
		($verbose) && print "id=[$id]<br />\n";
		if ($keyName && $id) {
			$object->$keyName = $id;
		}
		return true;
	}

	/**
	* Document::db_updateObject()
	*
	* { Description }
	*
	* @param [type] $updateNulls
	*/
	function updateObject( $table, &$object, $keyName, $updateNulls=true ) {
		$fmtsql = "UPDATE $table SET %s WHERE %s";
		$tmp = array();
		foreach (get_object_vars( $object ) as $k => $v) {
			if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
				continue;
			}
			if( $k == $keyName ) { // PK not to be updated
				$where = $keyName . '=' . $this->Quote( $v );
				continue;
			}
			if ($v === NULL && !$updateNulls) {
				continue;
			}
			if( $v == '' ) {
				$val = "''";
			} else {
				$val = $this->Quote( $v );
			}
			$tmp[] = $this->NameQuote( $k ) . '=' . $val;
		}
		$this->setQuery( sprintf( $fmtsql, implode( ",", $tmp ) , $where ) );
		return $this->query();
	}

	/**
	* @param boolean If TRUE, displays the last SQL statement sent to the database
	* @return string A standised error message
	*/
	function stderr( $showSQL = false ) {
		return "DB function failed with error number $this->_errorNum"
		."<br /><font color=\"red\">$this->_errorMsg</font>"
		.($showSQL ? "<br />SQL = <pre>$this->_sql</pre>" : '');
	}

	function insertid() {
		return mysql_insert_id( $this->_resource );
	}

	function getVersion() {
		return mysql_get_server_info( $this->_resource );
	}

	/**
	 * @return array A list of all the tables in the database
	 */
	function getTableList() {
		$this->setQuery( 'SHOW TABLES' );
		return $this->loadResultArray();
	}
	/**
	 * @param array A list of table names
	 * @return array A list the create SQL for the tables
	 */
	function getTableCreate( $tables ) {
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery( 'SHOW CREATE table ' . $this->getEscaped( $tblval ) );
			$rows = $this->loadRowList();
			foreach ($rows as $row) {
				$result[$tblval] = $row[1];
			}
		}

		return $result;
	}
	/**
	 * @param array A list of table names
	 * @return array An array of fields by table
	 */
	function getTableFields( $tables ) {
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery( 'SHOW FIELDS FROM ' . $tblval );
			$fields = $this->loadObjectList();
			foreach ($fields as $field) {
				$result[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type );
			}
		}

		return $result;
	}

	/**
	* Fudge method for ADOdb compatibility
	*/
	function GenID( $foo1=null, $foo2=null ) {
		return '0';
	}
}
?>