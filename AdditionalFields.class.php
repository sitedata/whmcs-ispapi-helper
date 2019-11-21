<?php

namespace ISPAPI;

class AdditionalFields extends \WHMCS\Domains\AdditionalFields
{
    /**
     * @param array $command API command to add additional domain field parameters to
     * @param string $registrantcountry country of the registrant
     */
    public function addToCommand(&$command, $registrantcountry) {
        foreach($this->getFields() as $fieldKey => $values){
            $iname = $this->getConfigValue($fieldKey, "Ispapi-Name");
            if (empty($iname)){
                continue;
            }
            $ignoreCountries = $this->getConfigValue($fieldKey, "Ispapi-IgnoreForCountries");
            if (!(
                empty($ignoreCountries) || 
                (!empty($registrantcountry) && !in_array(strtoupper($registrantcountry), $ignoreCountries))
            )){
                continue;
            }
            $type = $this->getConfigValue($fieldKey, "Type");
            $val = $this->getConfigValue($fieldKey, "Default");
            if ($this->getFieldValue($fieldKey) !== "") {
                $val = $this->getFieldValue($fieldKey);
            }
            if ($type == "tickbox") {
                $val = ($val) ? 1 : 0;
            }
            $format = $this->getConfigValue($fieldKey, "Ispapi-Format");
            if (!empty($format) && $format == "UPPERCASE") {
                $val = strtoupper($val);
            }
            if (!empty($val)){
                $command[$iname] = $val;
            }
        }
    }

    //untested! TODO
    public function setFieldValuesFromAPI($r) {
        if ($r["CODE"] == "200"){
            $data = [];
            $r = $r["PROPERTY"];
            foreach($this->getFields() as $fieldKey => $values){
                $type = $this->getConfigValue($fieldKey, "Type");
                $iname = $this->getConfigValue($fieldKey, "Ispapi-Name");
                $name = $this->getConfigValue($fieldKey, "Name");
                $defaultval = $this->getConfigValue($fieldKey, "Default");
                if (isset($r[$iname][0])){
                    $data[$name] = $r[$iname][0];
                    if ($type == "tickbox" && $data[$name] == "1"){
                        $data[$name] = $defaultval;
                    }
                }
            }
            parent::setFieldValues($data);    
        } 
    }
}

?>