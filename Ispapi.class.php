<?php

namespace ISPAPI;

class Ispapi
{
    /**
    * Check if providing Admin-C in Trade is necessary
    * @param string $tld last segment of the tld
    * @return bool
    */
    public static function needsAdminContactInTrade($tld)
    {
        //see https://wiki.hexonet.net/wiki/IT "Ownerchange"
        //see https://wiki.hexonet.net/wiki/ES "Ownerchange"
        //if the new registrant is an individual then the admin contact is required and has to match the new registrant contact
        return in_array($tld, ["it", "es"]);
    }
}
