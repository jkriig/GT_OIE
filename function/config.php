<?php
return array(
   
    /*
      Configuration for the local site's ldap directory
    */
    "ldap" => array(
      "host" => "ldaps://r.gted.gatech.edu",
      "port" => 636,
      "rdn"=>"uid=_________,ou=local accounts,dc=gted,dc=gatech,dc=edu",
      "tree"=> "ou=people,dc=gted,dc=gatech,dc=edu",
      "password" => "BADPASSWORDS",
      "rfidCardLength" => 9,
      "timeout" => 3,
    )  
);

?>
