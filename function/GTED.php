<?php
require_once('Helpers.php');
Class GTED {
  private $ldapConfig;
  
 
  function __construct () {
    $config = require("config.php");
    $this->ldapConfig = $config["ldap"];
    $this->tree = $this->ldapConfig["tree"];
    $this->rfidCardLength = $this->ldapConfig["rfidCardLength"];
    $this->link = null; //LDAP connection stored here
  }
  private function _connect2Ldap() {
	putenv('LDAPTLS_REQCERT=never');
    $ldapConfig = $this->ldapConfig;	
    $this->link = $ldapconn = ldap_connect($ldapConfig["host"], $ldapConfig["port"]);
    //Have ldap timeout if it cannot connect after $ldapConfig['timeout'] seconds
    ldap_set_option($this->link, LDAP_OPT_NETWORK_TIMEOUT,$ldapConfig['timeout']);	    
    if(!$this->link) {
      throw new Exception("Unable to connect to the LDAP database");
    } else {
      $ldapBind = ldap_bind($this->link, $ldapConfig["rdn"], $ldapConfig["password"]);
	  if(!$ldapBind){
		  if (ldap_get_option($this->link, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error)) {
				throw new Exception("Error Binding to LDAP: $extended_error");
			} else {
				echo "Error Binding to LDAP: No additional information is available.";
			}
	  }
    }
  }
 
  
  public function _getByGTID($gtid) {
    
    $ldapData = $this->queryGTID($gtid);    
    if($ldapData) {		
      return $ldapData; //return ldap
    } else {
      return false;
    }
  }
  /*
    Gets result using the buzzcardId field
  */
  public function _getByBuzzcardId($buzzcardId) {
    
    $ldapData = $this->queryBuzzCard($buzzcardId);
    //if ldap hit
    if($ldapData) {      
      return $ldapData; //return ldap
    } else {
      return false;
    }
  }

 
  public function query($params) {
    //make sure if we've connected to ldap
    if(!$this->link) {
      $this->_connect2Ldap();
    }
    //Default settings
    $from = 0;
    $maxSize = 25;
    $ldapQuery = "";
    //assing params to query or query settings
    foreach($params as $key=>$value) {
      if($key === "from") {
        $from = $value;
      } else if($key === "maxSize") {
        $maxSize = $value;
      } else { //add to query
        $ldapQuery .= "($key=$value)";
      }
    }
    $results = ldap_search($this->link, $this->tree, $ldapQuery);
    if(!$results) {
      return false;
    } else {
      return $this->filter($results, $from, $maxSize);
    }
  }
  /*
    Filters down the results from the given from/maxsize params
    if no results match params, then returns a boolean false
  */
  private function filter($result, $from, $maxSize) {
    $output = array();
    $currEntry = 0;
    $entries = ldap_get_entries($this->link, $result);
    $numEntries = $entries["count"];
    for($i = $from; $i < $maxSize && $i < $numEntries; ++$i) {
      array_push($output, $entries[$i]);
    }
    //If no results return false
    if(count($output) === 0) {
      $output = false;
    }
    return $output;
  }
  
  public function queryBuzzCard($buzzCardId) {
    //padd BuzzCardId with 0s if necessary
    $buzzCardId = $this->padWithZeros($buzzCardId, $this->rfidCardLength);

    return $this->firstResult($this->query(array(
      "gtaccesscardnumber" => $buzzCardId,
      "from" => 0,
      "maxSize" => 1
    )));
  }
  /*
    Queries GTED for the first result matching the given GTID
    returns LDAP entry or false (No results match)
  */
  public function queryGTID($gtid) {
    return $this->firstResult($this->query(array(
      "gtgtid" => $gtid,
      "from" => 0,
      "maxSize" => 1
    )));
  }
  /*
    Queries GTED for the first result matching the given gt-username
    returns ldap entry or false (no results match)
  */
  public function queryGTUsername($username) {
    return $this->firstResult($this->query(array(
      "gtprimarygtaccountusername" => $username,
      "from" => 0,
      "maxSize" => 1
    )));
  }
  /*
    Padds the $string2pad with leading 0s.
    E.g: padWithZeros("323125", 9) === "000323125"
  */
  public function padWithZeros($string2Pad, $wantedLength) {
    $padding = "";
    for($i = strlen($string2Pad); $i < $wantedLength; ++$i) {
        $padding .= '0';
    }
    return $padding . $string2Pad;
  } 
  /*
    Get just the first query result
  */
  public function firstResult($queryResult) {
    if($queryResult !== false) {
      return $queryResult[0]; //just send ldap entry
    } else {
      return false;
    }
  }
  
  /*
    Returns an object with more sane object key's
  */
  /* gets User info */
   public function getUser($userId) {
    $output = false;
    $isNumeric = is_numeric($userId);
    $strLength = strlen('' . $userId);
    $bitLength = strlen('' . decbin(intval($userId)));
    //check if gt-username
    if($isNumeric && $strLength == 9) {
      $output = $this->_getByGTID(strval($userId));
    //check if parsed out buzzcard id
    } else if($isNumeric && $strLength >= 6 && $bitLength > 19) { 
      $output = $this->_getByBuzzcardId(Helpers::parseRawBuzzCard($userId)); 
    }         
    return $output;
  }
  
}


?>