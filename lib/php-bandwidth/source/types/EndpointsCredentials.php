<?php
/**
 * @type EndpointsCredentials
 *
 * Minimal interface to store
 * credentials. __toString should serialize
 */

namespace Catapult; 

final class EndpointsCredentials{
  public function __construct() {
    $data = Ensure::Input(func_get_args());
    return $this->setup($data->get());
  }

  /**
   * check if we're 
   * given the needed
   * keys. Additionally add
   * client checks 
   *
   * password > 6 characters
   * realm is four point
   * username no check here
   */
  public function setup($data) {
    foreach ($data as $k => $d) {
      if (!in_array($k,array("username","password","realm"))) {
        throw new CatapultApiException("$k is not a valid key for endpoint credentials");
      }
    }
    $this->username = $data['username'];
    $this->password = $data['password'];
    $this->realm = $data['realm'];
    // important don't serialize
    // when sending in a Endpoints object
    $this->serialize = FALSE;    
  }

  public function __toString() {
    return json_encode(array(
      "username" => $this->username,
      "password" => $this->password,
      "realm" => $this->realm
    )); 
  }
}
