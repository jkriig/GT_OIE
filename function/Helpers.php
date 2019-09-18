<?php

class Helpers {
  public static function id2Int($id) {
    if(!is_numeric($id)) {
      throw new Exception("Id is not a number: " . $id); return;
    }
    if($id < 0) {
      throw new Exception("Id must be a + number >= 0");
    }
    return intval($id);
  }
  /*
    Takes in raw RFID data stored as a string or number.
    and returns the unique student identifier (buzzcard id)
    -1 = invalid id
    anything else = valid id
  */
  public static function parseRawBuzzCard($cardData) {
    if(!is_numeric($cardData)) {
      return -1;
    }
    $binaryCardData = '' . decbin(intval($cardData));
    //If leading zeros were taken off in transit, re-add them
    for($i = strlen($binaryCardData); $i < 35; ++$i) {
      $binaryCardData = '0' . $binaryCardData;
    }

    //Parse out the userId
    $ID_START_BIT = 14;
    $ID_BIT_LENGTH = 20;
    $binaryUserId = '';
    for($i = $ID_START_BIT; $i < $ID_START_BIT + $ID_BIT_LENGTH; ++$i) {
      $binaryUserId .= $binaryCardData[$i];
    }
    return intval($binaryUserId, 2);

  }
}

?>