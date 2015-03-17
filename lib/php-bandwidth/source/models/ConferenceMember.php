<?php

/**
 * @model ConferenceMember
 * https://catapult.inetwork.com/docs/api-docs/conferences/
 * 
 * Functions to create, update and delete
 * conference members
 *
 */

namespace Catapult;

/* Represent a member in a conference
 * methods to get audio files, get member
 * and update
 */
final class ConferenceMember extends AudioMixin {
   /**
    * CTor for conference memebrs 
    *
    * Init forms:
    * GET
    * ConferenceMember('member-id')
    * ConferenceMember()
    * 
    * POST
    * ConferenceMember('conference-id', array)
    * ConferenceMember(array)
    */
    public function __construct()
    {
      $data = Ensure::Input(func_get_args());
        parent::_init($data, new DependsResource(array(
            array("term" => "conference", "plural" => true, "silent" => false, "mandatoryId" => true))
           ),
           new LoadsResource(array("primary" => "GET", "init" => array("conferenceId"), "id" => "id", "silent"=>FALSE)),
           new SchemaResource(array("fields" => array(
                'id', 'state', 'addedTime', 'hold', 'mute', 'joinTone', 'leavingTone'
            ), "needs" => array("id", "state", "from"))
          )
       );
    }

    /**
     * Get audio url
     * for conference member
     */
    public function getAudioUrl()
    {
      return URIResource::Make($this->path, array($this->conference, "members", "audio"));
    }
}
?>
