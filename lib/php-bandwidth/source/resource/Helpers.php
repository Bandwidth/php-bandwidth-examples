<?php

namespace Catapult;
/**
 * All these are used directly by the models
 * they are passed in the constructors and validate,
 * and make the models. Order of execution follows
 * DependsResource, LoadsResource, SchemaResource, SubFunctionResource (optional)
 *
 * TODO make metaresurce initializa
 * more than only the SubFunctions
 */

abstract class MetaResource extends BaseResource {
    public function __construct($depends=null) {
        $this->terms = array();

        if (!is_array($depends))
            return;

        foreach ($depends as $k => $d) {
            $this->terms[$k] = new SubFunctionObject($d);
        }

    }
}

/**
 * General purpose carrying of multiple
 * parameter objects for Schema, Depends objects
 * 
 * needs subclass to inherit with terms
 */
abstract class Multi {
    public function __construct($args) {
        if (is_array($args)) {
           
          foreach ($this->terms as $t) {
             if (!in_array($t, array_keys($args))) {
                $this->$t = false;
                continue;
             }
             
              $this->$t = $args[$t];
          } 
       }
    }
}


final class SubfunctionObject extends Multi {
    public $terms = array( "term", "type", "singular" ); 
}

/**
 * object for dependancies
 * in models. Used with DependsResource
 *
 */
final class DependsObject extends Multi {
    public $terms = array(
        "term",
        "plural",
        "mandatoryId" 
    ); 
}

/**
 * append information to the object
 * before passing it to the CTor
 */
final class AppendsObject extends Multi {
  public $terms = array(
    "term",
    "link",
    "value"
  );
}

/**
 * subfunction resource will register
 * functions that are derived from another object
 * i.e
 * in calls:
 * getTranscriptions
 * this will be however defined in calls. 
 * 
 */
class SubFunctionResource extends MetaResource {
    public static $terms = array(
        "term",
        "type",
        "id",
        "plural"
    );
}


/**
 * Depends resource, these
 * distinguish whats needed for a model
 * primarly used for building paths
 * and finding out whether to create or
 * get a model on initialization
 * each model should have this embedded
 */

final class DependsResource extends BaseResource {
    public static $terms = array(
        "plural",
        "term",
        "mandatoryId"
    );
    /**
     * fields known to depends,
     * 'plural', 'mandatoryId'
     *
     * in some cases we look
     * for plural terms and 
     * ids 'not' being plural
     * you can set this in the assoc array
     *
     */
    public function __construct($depends=null) {
        $this->terms = array();

        if (!is_array($depends))
            return;

        foreach ($depends as $k => $d) {
            if (!in_array($k, $this::$terms))
                throw new \CatapultApiException("Fields were built improperly for " . __CLASS__);

            $this->terms[$k] = new DependsObject($d);
        }
    }
}

/**
 * constraints resource
 * should qualify membership in
 * an object only when a term matches
 * MessageEvent("direction" => "incoming")) with array("direction" => "incoming")
 */
final class ConstraintsResource {
  /**
   * this will
   * attach the terms to an object
   *
   * make sure its
   * @param object: Catapult Model or Event
   */
  public function Make(&$object) {
    $object->constraints = &$this;
  }
  /**
   * construct the terms
   * usually passed in
   * as array
   * 
   * @param terms
   */
  public function __construct($terms) {
    $this->terms = array();
    foreach ($terms as $k => $term) {
      $this->terms->$k = $term;
    }
  }
}

/**
 * sometimes we need information
 * from a parent model. And this information
 * is not provided directly from the returning objects
 * i.e recordings->listTranscriptions
 * this would return transcriptions w/o the recordingID
 * AppendsResource should allows for quick pointing of one recordingId
 * per several children
 *
 * Implementors Note: only useful for collections. The link option
 * will attach a value by reference
 *
 */
final class AppendsResource extends BaseResource {
  public static $terms = array(
    "term",
    "link",
    "value" 
  );

  public function Make(&$object, &$appends) {
    foreach($appends->data as $ap) {
      $prop = $ap->term;
      /** is it by reference? **/
      if ($ap->link) {
        $value = &$ap->value;
        $object->$prop = &$value;
      } else {
        $value = $ap->value;
        $object->$prop = $value;
      }
    }
  }
  public function __construct($appends) {
    if (!is_array($appends)) {
      return;
    }

    foreach($appends as $k => $a) {
      if (!in_array($k, self::$terms)) {
        throw new \CatapultApiException("Fields were built improperly for " . __CLASS__);
      }
      $this->data[$k] =new AppendsObject($a);
    }
  }
}


/**
 * Client resource has one function it attaches
 * the main client to this model, by reference
 * outcome should be all objects pointing to the
 * same client
 *
 * when no client is available throw a warning
 * this helps the user figure where the code
 * is faulty
 */
class ClientResource extends BaseResource {
    public static function attach(&$object) {
        $object->client = &Client::Get();
        if ($object->client == null)
            throw new \CatapultApiException("You have not initialized the client yet. Please use: Catapult\Client(params..)");
    }
}

?>
