<?php

namespace ISPAPI;

class AdditionalFields extends \WHMCS\Domains\AdditionalFields
{
    public static $transpfx = "hxflags"; // translation key prefix
    public static $isOTE = false;
    public static $entity = "LIVE";
    public static $additionalfieldscfg = [
        "OTE"=>null,
        "LIVE"=>null
    ];

    public static function init($isOTE)
    {
        self::$isOTE = $isOTE;
        self::$entity = $isOTE ? "OTE" : "LIVE";
        if (!is_null(self::$additionalfieldscfg[self::$entity])) {
            return;
        }
        self::$additionalfieldscfg[self::$entity] = [
            "register" => [
                ".abogado" => [ self::getHighlyRegulatedTLDField(".abogado") ],
                ".ae" => [ self::getRegulatedTLDField(".ae") ],
                ".aero" => [
                    [
                        'Name' => '.AERO ID',
                        'Ispapi-Name' => "X-AERO-ENS-AUTH-ID"
                    ],
                    [
                        'Name' => '.AERO Key',
                        'Ispapi-Name' => "X-AERO-ENS-AUTH-KEY",
                        'Required' => true
                    ]
                ],
                ".asia" => self::disableWHMCSFields(["Legal Type", "Identity Form", "Identity Number"]),
                ".attorney" => [ self::getHighlyRegulatedTLDField(".attorney") ],//NOTAC
                ".bank" => [self::getAllocationTokenField(".bank")],
                ".insurance" => [self::getAllocationTokenField(".insurance")],
                ".barcelona" => [self::getIntendedUseField()],
                ".cat" => [self::getHighlyRegulatedTLDField(".cat"), self::getIntendedUseField()],
                ".boats" => [ self::getHighlyRegulatedTLDField(".boats") ],
                ".broker" => [ self::getHighlyRegulatedTLDField(".broker") ],
                ".ca" => self::disableWHMCSFields('CIRA Agreement', [
                    self::getLegalTypeField(".ca", [
                        "Options" => [
                            "CCO", "CCT", "RES", "GOV", "EDU", "ASS", "HOS", "PRT", "TDM", "TRD",
                            "PLT", "LAM", "TRS", "ABO", "INB", "LGR", "OMK", "MAJ"
                        ],
                        "Ispapi-Name" => "X-CA-LEGALTYPE"
                    ]),
                    self::getLanguageField([
                        "Name" => "Contact Language",
                        "Options" => ["EN", "FR"],
                        "Required" => true,
                        "Ispapi-Name" => "X-CA-LANGUAGE"
                    ]),[
                        "Name" => "WHOIS Opt-out",
                        "LangVar" => "whoisoptout",
                        "Ispapi-Name" => "X-CA-DISCLOSE"
                    ]
                ]),
                ".cfd" => [ self::getHighlyRegulatedTLDField(".cfd") ],
                ".cn" => self::disableWHMCSFields(["cnhosting", "cnhregisterclause"], [
                    self::getContactIdentificationField("REGISTRANT", [
                        "Required" => true,
                        "Ispapi-Name" => "X-CN-REGISTRANT-ID-NUMBER"
                    ]),
                    self::getContactTypeField(".cn", "REGISTRANT", [
                        "Options" => [
                            "SFZ", "HZ", "GAJMTX", "TWJMTX", "WJLSFZ", "GAJZZ", "TWJZZ", "JGZ",
                            "ORG", "YYZZ", "TYDM", "BDDM", "JDDWFW", "SYDWFR", "WGCZJG", "SHTTFR",
                            "ZJCS", "MBFQY", "JJHFR", "LSZY", "WGZHWH", "WLCZJG", "SFJD", "JWJG",
                            "SHFWJG", "MBXXBX", "YLJGZY", "GZJGZY", "BJWSXX", "QT"
                        ],
                        "Ispapi-Name" => "X-CN-REGISTRANT-ID-TYPE"
                    ])
                ]),
                ".com.br" => [
                    self::getRegistrantIdentificationField(".com.br", [
                        "Name" => "Identification Number",
                        "LangVar" => "identificationnumber",
                        "Description" => "combridentificationnumberdescr",
                        "Ispapi-Name" => "X-BR-REGISTER-NUMBER"
                    ])
                ],
                ".com.au" => self::disableWHMCSFields(
                    [ "Registrant Name", "Eligibility Name", "Eligibility ID", "Eligibility ID Type", "Eligibility Type", "Eligibility Reason"],
                    [
                        self::getContactIdentificationField("REGISTRANT", [
                            "Required" => true,
                            "Ispapi-Name" => "X-CN-REGISTRANT-ID-NUMBER"
                        ]),
                        self::getContactTypeField(".com.au", "REGISTRANT", [
                            "Required" => true,
                            "Options" =>  [ "ABN", "ACN", "RBN", "TM" ]
                        ])
                    ]
                ),
                ".de" => self::disableWHMCSFields(
                    [ "Tax ID", "Address Confirmation", "Agree to DE Terms"],
                    [
                        self::getContactIdentificationField("", [
                            "Name" => "General Request Contact",
                            "Description" => "dkgeneralrequestcontactdescr",
                            "Ispapi-Name" => "X-DE-GENERAL-REQUEST",
                            "LangVar" => "degeneralrequestcontact"
                        ]),
                        self::getContactIdentificationField("", [
                            "Name" => "Abuse Team Contact",
                            "Description" => "dkabuseteamcontactdescr",
                            "Ispapi-Name" => "X-DE-ABUSE-CONTACT",
                            "LangVar" => "deabuseteamcontact"
                        ])
                    ]
                ),
                ".dentist" => [ self::getHighlyRegulatedTLDField(".dentist") ],//NOTAC
                ".dk" => [
                    self::getLegalTypeField(".dk", [
                        "Name" => "Registrant Legal Type",
                        "LangVar" => "dktldregistrantlegaltype",
                        "Description" => "dktldlegaltypedescr",
                        "Ispapi-CmdRemove" => [
                            "INDIV" => [
                                "OWNERCONTACT0" => "ORGANIZATION"
                            ]
                        ]
                    ]),
                    self::getVATIDField(".dk", "REGISTRANT"),
                    self::getContactIdentificationField("", [
                        "Name" => "Registrant Contact",
                        "Description" => "dktldcontactdescr",
                        "Ispapi-Name" => "X-DK-REGISTRANT-CONTACT",
                        "LangVar" => "dkregistrantcontact"
                    ]),
                    self::getLegalTypeField(".dk", [
                        "Name" => "Admin Legal Type",
                        "LangVar" => "dktldadminlegaltype",
                        "Description" => "dktldlegaltypedescr",
                        "Ispapi-CmdRemove" => [
                            "INDIV" => [
                                "ADMINCONTACT0" => "ORGANIZATION"
                            ]
                        ]
                    ]),
                    self::getVATIDField(".dk", "ADMIN"),
                    self::getContactIdentificationField("", [
                        "Name" => "Admin Contact",
                        "Description" => "dktldcontactdescr",
                        "Ispapi-Name" => "X-DK-ADMIN-CONTACT",
                        "LangVar" => "dkadmincontact"
                    ])
                ],
                ".eco" => [ self::getHighlyRegulatedTLDField(".eco") ],
                ".es" => self::disableWHMCSFields(
                    [ "ID Form Type", "ID Form Number", 'Entity Type'],
                    [
                        self::getIndividualRegulatedTLDField(".es"),
                        self::getContactTypeField(".es", "REGISTRANT", [
                            "Options" => [ "0", "1", "3" ],
                            "Required" => true,
                            "Ispapi-Name" => "X-ES-REGISTRANT-TIPO-IDENTIFICACION"
                        ]),
                        self::getContactIdentificationField("REGISTRANT", [
                            "Name" => "Registrant Identification Number",
                            "Required" => true,
                            "Ispapi-Name" => "X-ES-REGISTRANT-IDENTIFICACION",
                            "LangVar" => "esregistrantidentificationnumber"
                        ]),
                        self::getContactTypeField(".es", "ADMIN", [
                            "Options" => [ "0", "1", "3" ],
                            "Required" => true,
                            "Ispapi-Name" => "X-ES-REGISTRANT-TIPO-IDENTIFICACION"
                        ]),
                        self::getContactIdentificationField("ADMIN", [
                            "Name" => "Admin Identification Number",
                            "Required" => true,
                            "Ispapi-Name" => "X-ES-ADMIN-IDENTIFICACION",
                            "LangVar" => "esadminidentificationnumber"
                        ]),
                        self::getLegalTypeField(".es", [
                            "Options" => [
                                "", "1", "39", "47", "59", "68", "124", "150", "152", "164", "181", "197", "203", "229", "269", "286", "365",
                                "434", "436", "439", "476", "510", "524", "525", "554", "560", "562", "566", "608", "612", "713", "717", "744",
                                "745", "746", "747", "878", "879", "877"
                            ],
                            "Required" => false,
                            "Ispapi-Name" => "X-ES-REGISTRANT-FORM-JURIDICA"
                        ])
                    ]
                ),
                ".eu" => self::disableWHMCSFields(
                    [ 'Entity Type' ],
                    [
                        self::getCountryField([
                            "Name" => "Registrant Citizenship",
                            "Options" => ["", "AT", "BE", "BG", "CZ", "CY", "DE", "DK", "ES", "EE", "FI", "FR", "GR", "HU", "IE", "IT", "LT", "LU", "LV", "MT", "NL", "PL", "PT", "RO", "SE", "SK", "SI", "HR"],
                            "Description" => "euregistrantcitizenshipdescr",
                            "Ispapi-Name" => "X-EU-REGISTRANT-CITIZENSHIP"
                        ])
                    ]
                ),
                ".fi" => [
                    self::getRegulatedTLDField(".fi"),
                    self::getRegistrantIdentificationField(".fi", [
                        "Required" => false,
                        "Description" => "firegistrantidnumberdescr"
                    ]),
                    [
                        "Name"  => "Registrant Birthdate",
                        "LangVar" => "registrantbirthdate",
                        "Type"  => "text",
                        "Description" => "firegistrantbirthdatedescr"
                    ]
                ],
                ".forex" => [ self::getHighlyRegulatedTLDField(".forex") ],
                ".fr" => self::getAFNICFields(),
                ".gay" => [
                    self::getRegulatedTLDField(".gay", [
                        "Ispapi-Name" => "X-GAY-ACCEPT-REQUIREMENTS"
                    ])
                ],
                ".health" => [ self::getHighlyRegulatedTLDField(".health") ],
                ".hk" => self::disableWHMCSFields(
                    [
                        "Registrant Type", 'Organizations Name in Chinese', 'Organizations Supporting Documentation',
                        'Organizations Document Number', 'Organizations Issuing Country', 'Organizations Industry Type',
                        'Individuals Supporting Documentation', 'Individuals Document Number', 'Individuals Issuing Country',
                        'Individuals Under 18'
                    ],
                    [
                        self::getIndividualRegulatedTLDField(".hk", [
                            "Required" => [
                                "Registrant Document Type" => [
                                    "HKID",
                                    "OTHID",
                                    "PASSNO",
                                    "BIRTHCERT",
                                    "OTHIDV"
                                ]
                            ]
                        ]),
                        [
                            "Name" => "Registrant Document Type",
                            "Type" => "dropdown",
                            "Options" => self::getOptions(".hk", "Registrant Document Type", [
                                "HKID", "OTHID", "PASSNO", "BIRTHCERT", "OTHIDV", "BR", "CI", "CRS", "HKSARG",
                                "HKORDINANCE", "OTHORG"
                            ]),
                            "Description" => "hkregistrantdocumenttypedescr",
                            "Required" => true,
                            "Ispapi-Name" => "X-HK-REGISTRANT-DOCUMENT-TYPE"
                        ],[
                            "Name" => "Registrant Other Document Type",
                            "Type" => "text",
                            "Required" => [
                                'Registrant Document Type' => [
                                    'OTHIDV',
                                    'OTHORG'
                                ]
                            ],
                            "Description" => "hkregistrantotherdocumenttypedescr",
                            "Ispapi-Name" => "X-HK-REGISTRANT-OTHER-DOCUMENT-TYPE"
                        ],[
                            "Name" => "Registrant Document Number",
                            "Type" => "text",
                            "Required" => true,
                            "Ispapi-Name" => "X-HK-REGISTRANT-DOCUMENT-NUMBER"
                        ],
                        self::getCountryField([
                            "Name" => "Registrant Document Origin Country",
                            "Required" => true,
                            "Options" => "ALL",
                            "Ispapi-Name" => "X-HK-REGISTRANT-DOCUMENT-ORIGIN-COUNTRY"
                        ]),
                        [
                            "Name" => "Registrant Birth Date for individuals",
                            "Type" => "text",
                            "Required" => [
                                "Registrant Document Type" => [
                                    "HKID",
                                    "OTHID",
                                    "PASSNO",
                                    "BIRTHCERT",
                                    "OTHIDV"
                                ]
                            ],
                            "Description" => "hkregistrantbirthdateforindividualsdescr",
                            "Ispapi-Name" => "X-HK-REGISTRANT-BIRTH-DATE"
                        ]
                    ]
                ),
                ".homes" => [ self::getHighlyRegulatedTLDField(".homes") ],
                ".id" => [ self::getHighlyRegulatedTLDField(".id") ],
                ".ie" => [
                    self::getLegalTypeField(".ie", [
                        "Name" => "Registrant Class",
                        "Options" => [
                            "Company", "Business Owner", "Club/Band/Local Group", "School/College", "State Agency",
                            "Charity", "Blogger/Other"
                        ],
                        "Required" => true,
                        "Ispapi-Name" => "X-IE-REGISTRANT-CLASS"
                    ]),
                    [
                        "Name" => "Proof of connection to Ireland",
                        "Type" => "text",
                        "Description" => "ieproofofconnectiontoirelanddescr",
                        "Required" => true,
                        "Ispapi-Name" => "X-IE-REGISTRANT-REMARKS",
                    ],
                    self::getRegistrantIdentificationField(".ie", [
                        "Required" => false
                    ]),
                    self::getVATIDField(".ie", "REGISTRANT", [
                        "Required" =>  ["Registrant Class" => ["Company"]]
                    ])
                ],
                ".it" => self::disableWHMCSFields(
                    [ "Legal Type", "Tax ID", "Publish Personal Data" ],
                    [
                        self::getRegulatedTLDField(".it", "section3", [
                            "Name" => "Accept Section 3",
                            "Ispapi-Name" => "X-IT-ACCEPT-LIABILITY-TAC"
                        ]),
                        self::getRegulatedTLDField(".it", "section5", [
                            "Name" => "Accept Section 5",
                            "Ispapi-Name" => "X-IT-ACCEPT-REGISTRATION-TAC",
                            "Required" => false
                        ]),
                        self::getRegulatedTLDField(".it", "section6", [
                            "Name" => "Accept Section 6",
                            "Ispapi-Name" => "X-IT-ACCEPT-DIFFUSION-AND-ACCESSIBILITY-TAC"
                        ]),
                        self::getRegulatedTLDField(".it", "section7", [
                            "Name" => "Accept Section 7",
                            "Ispapi-Name" => "X-IT-ACCEPT-EXPLICIT-TAC"
                        ]),
                        [
                            "Name" => "PIN",
                            "Type" => "text",
                            "Ispapi-Name" => "X-IT-PIN"
                        ]
                    ]
                ),
                ".jobs" => [
                    [
                        "Name" => "Website",
                        "Ispapi-Name" => "X-JOBS-COMPANYURL"
                    ],
                    self::getLegalTypeField(".jobs", [
                        "Name" => "Industry Classification",
                        "Options" => [
                            "2", "3", "21", "5", "4", "12", "6", "7", "13", "19", "10", "11", "15",
                            "16", "17", "18", "20", "9", "26", "22", "14", "23", "8", "24", "25"
                        ],
                        "Required" => true,
                        "Ispapi-Name" => "X-JOBS-INDUSTRYCLASSIFICATION"
                    ]),
                    self::getYesNoField(".jobs", [
                        "Name" => "Member of a HR Association",
                        "Ispapi-Name" => "X-JOBS-HRANAME"
                    ]),
                    [
                        "Name" => "Contact Job Title",
                        "Type" => "text",
                        "Required" => true,
                        "Ispapi-Name" => "X-JOBS-TITLE"
                    ],
                    self::getContactTypeField(".jobs", "", [
                        "Name" => "Contact Type",
                        "Options" => ["1", "0"],
                        "Ispapi-Name" => "X-JOBS-ADMINTYPE"
                    ])
                ],
                ".lotto" => [
                    [
                        "Name" => "Membership Contact ID",
                        "Type" => "text",
                        "Required" => true,
                        "Ispapi-Name" => "X-ASSOCIATION-ID"
                    ],[
                        "Name" => "Verification Code",
                        "Type" => "text",
                        "Required" => true,
                        "Ispapi-Name" => "X-ASSOCIATION-AUTH"
                    ]
                ],
                ".lv" => [
                    self::getVATIDField(".lv", "REGISTRANT", [
                        "Required" => false,
                        "Ispapi-Name" => "X-VATID"
                    ]),
                    self::getRegistrantIdentificationField(".lv", [
                        "Required" => false,
                        "Ispapi-Name" => "X-IDNUMBER"
                    ])
                ],
                ".law" => [ self::getHighlyRegulatedTLDField(".law") ],
                ".lawyer" => [ self::getHighlyRegulatedTLDField(".lawyer") ],//NOTAC
                ".lt" => [
                    [
                        "Name" => "Legal Entity Identification Code",
                        "Type" => "text",
                        "Required" => false,
                        "Ispapi-Name" => "X-LT-REGISTRANT-LEGAL-ID"
                    ]
                ],
                ".madrid" => [ self::getIntendedUseField() ],
                ".makeup" => [ self::getHighlyRegulatedTLDField(".makeup") ],//NOTAC
                ".markets" => [ self::getHighlyRegulatedTLDField(".markets") ],
                ".melbourne" => [
                    self::getNexusCategoryField(".melbourne", [
                        "Options" => [ "A", "B", "C" ],
                        "Description" => "melbournenexuscategorydescr"
                    ])
                ],
                ".mk" => [
                    self::getVATIDField(".mk", "REGISTRANT", [
                        "Required" => false
                    ]),
                    self::getContactIdentificationField("REGISTRANT")
                ],
                ".my" => [
                    self::getContactTypeField(".my", "REGISTRANT", [
                        "Name" => "Registrant Organisation Type",
                        "LangVar" => "myregistrantorganisationtype",
                        "Options" => [
                            "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11", "12", "13", "14", "15", "16",
                            "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"
                        ],
                        "Required" => false,
                        "Ispapi-Name" => "X-MY-REGISTRANT-ORGANIZATION-TYPE"
                    ]),
                    self::getContactIdentificationField("REGISTRANT"),
                    self::getContactIdentificationField("ADMIN"),
                    self::getContactIdentificationField("TECH"),
                    self::getContactIdentificationField("BILLING")
                ],
                ".ngo" => [ self::getRegulatedTLDField(".ngo") ],
                ".no" => [
                    self::getRegistrantIdentificationField(".no", [
                        "Ispapi-Name" => "X-NO-REGISTRANT-IDENTITY"
                    ]),
                    self::getFaxFormField()
                ],
                ".nu" => [
                    self::getRegulatedTLDField(".nu"),
                    self::getContactIdentificationField("REGISTRANT"),
                    self::getVATIDField(".nu", "REGISTRANT", [
                        'Required' => false,
                        "Ispapi-Name" => "X-VATID"
                    ])
                ],
                ".nyc" => [
                    self::getNexusCategoryField(".nyc", [
                        "Options" => [ "1", "2" ],
                        "Description" => "nycnexuscategorydescr",
                        "Ispapi-Name" => "X-NYC-REGISTRANT-NEXUS-CATEGORY"
                    ])
                ],
                ".paris" => [ self::getRegulatedTLDField(".paris") ],
                ".pl" => self::disableWHMCSFields([ 'Publish Contact in .PL WHOIS' ]),
                ".pt" => [
                    self::getVATIDField(".pt", "REGISTRANT", [
                        'Required' => false,
                        "Ispapi-Name" => "X-PT-REGISTRANT-VATID"
                    ]),
                    self::getVATIDField(".pt", "TECH", [
                        'Required' => false,
                        "Ispapi-Name" => "X-PT-TECH-VATID"
                    ])
                ],
                ".quebec" => self::disableWHMCSFields([ "Info" ], [ self::getIntendedUseField() ]),
                ".ro" => self::disableWHMCSFields([ 'CNPFiscalCode', "Registrant Type"], [
                    self::getContactIdentificationField("REGISTRANT"),
                    self::getVATIDField(".ro", "REGISTRANT", [
                        'Description' => "roregistrantvatiddescr",
                        'Required' => false
                    ])
                ]),
                ".ru" => self::disableWHMCSFields(
                    [
                      'Registrant Type', 'Individuals Passport Number', 'Individuals Passport Issuer', 'Individuals Passport Issue Date',
                      'Individuals: Whois Privacy',  'Russian Organizations Taxpayer Number 1', 'Russian Organizations Territory-Linked Taxpayer Number 2'
                    ],
                    [
                        self::getRegulatedTLDField(".ru"),
                        self::getLegalTypeField(".ru"),
                        [
                            'Name'  => 'Individuals Birthday',
                            "Required" => [
                                "Legal Type" => [
                                    "INDIV"
                                ]
                            ],
                            "Ispapi-Name" => "X-RU-REGISTRANT-BIRTH-DATE"
                        ], [
                            "Name" => "Individuals Passport Data",
                            "Description" => "ruindividualspassportdatadescr",
                            "Type" => "text",
                            "Required" => [
                                "Legal Type" => [
                                    "INDIV"
                                ]
                            ],
                            "Ispapi-Name" => "X-RU-REGISTRANT-PASSPORT-DATA"
                        ]
                    ]
                ),
                ".scot" => [self::getIntendedUseField()],
                ".se" => [
                    self::getRegulatedTLDField(".se"),
                    [
                        "Name" => "Identification Number",
                        "LangVar" => "identificationnumber",
                        "Description" => "seidentificationnumberdescr",
                        "Ispapi-Name" => "X-NICSE-IDNUMBER"
                    ],[
                        "Name" => "VAT ID",
                        "LangVar" => "registrantvatid",
                        "Ispapi-Name" => "X-NICSE-VATID"
                    ]
                ],
                ".sg" => self::disableWHMCSFields(["Registrant Type"], [
                    [
                        "Name" => "RCBID",
                        "Required" => false,
                        "Ispapi-Name" => "X-SG-RCBID"
                    ],
                    self::getContactIdentificationField("ADMIN")
                ]),
                ".sport" => [ self::getIntendedUseField() ],
                ".spreadbetting" => [ self::getHighlyRegulatedTLDField(".spreadbetting") ],
                ".swiss" => [
                    [
                        "Name" => "Registrant Enterprise ID",
                        "Description" => "swissregistrantenterpriseiddescr",
                        "Default" => "CHE",
                        "Ispapi-Name" => "X-SWISS-REGISTRANT-ENTERPRISE-ID"
                    ],
                    self::getIntendedUseField()
                ],
                ".sydney" => [
                    self::getNexusCategoryField(".sydney", [
                        "Options" => [ "A", "B", "C" ]
                    ])
                ],
                ".tel" => [
                    self::getLegalTypeField(".tel", [
                        "Options" => [
                            "Natural",
                            "Legal"
                        ],
                        "Required" => true,
                        "Ispapi-Name" => "X-TEL-WHOISTYPE"
                    ]),
                    self::getYesNoField(".tel", [
                        "Name" => "WHOIS Opt-out",
                        "LangVar" => "whoisoptout",
                        "Description" => "telwhoisoptoutdescr",
                        "Ispapi-Name" => "X-TEL-PUBLISH",
                        "Options" => ["Y", "N"],
                        "Required" => false
                    ])
                ],
                ".trading" => [ self::getHighlyRegulatedTLDField(".trading") ],
                ".travel" => self::disableWHMCSFields(
                    ["Trustee Service", ".TRAVEL UIN Code", "Trustee Service Agreement ", ".TRAVEL Usage Agreement"],
                    [
                         self::getYesNoField(".travel", [
                            "Name" => ".TRAVEL Industry",
                            "Description" => "traveltravelindustrydescr",
                            "Ispapi-Name" => "X-TRAVEL-INDUSTRY",
                            "Options" => [ "1", "0" ],
                            "Required" => true
                         ])
                    ]
                ),
                ".uk" => self::disableWHMCSFields(["Legal Type", "Company ID Number", "Registrant Name", "WHOIS Opt-out"]),
                ".us" => [
                    self::getIntendedUseField(".us", [
                        "Ispapi-Name" => "X-US-NEXUS-APPPURPOSE",
                        "Type" => "dropdown",
                        "Options" => [ "P1", "P2", "P3", "P4", "P5" ]
                    ]),
                    self::getNexusCategoryField(".us", [
                        "Options" => [ "C11", "C12", "C21", "C31", "C32" ]
                    ]),
                    self::getCountryField([
                        "Name" => "Nexus Country",
                        "LangVar" => "nexuscountry",
                        "Description" => "usnexuscountrydescr",
                        "Ispapi-Name" => "X-US-NEXUS-VALIDATOR",
                        "Options" => "ALL",
                        "Required" => [
                            "Nexus Category" => [
                                "C31",
                                "C32"
                            ]
                        ]
                    ])
                ],
                ".vote" => self::disableWHMCSFields(["Agreement"]),
                ".voto" => self::disableWHMCSFields(["Agreement"]),
                ".xxx" => [
                    self::getRegulatedTLDField(".xxx"),
                    self::getYesNoField(".xxx", [
                        "Name" => "Non-Resolving Domain",
                        "Description" => "xxxnonresolvingdomaindescr",
                        "Ispapi-Name" => "X-XXX-NON-RESOLVING",
                        "Options" => [ "0", "1" ]
                    ], true),
                    [
                        "Name" => "Membership ID",
                        "Type" => "text",
                        "Description" => "xxxmembershipiddescr",
                        "Ispapi-Name" => "X-XXX-MEMBERSHIP-CONTACT",
                        "Required" => false
                    ]
                ],
                ".za" => [ self::getHighlyRegulatedTLDField(".za") ],
            ],
            "transfer" => [
                ".ca" => [
                    self::getLegalTypeField([
                        "Options" => [
                            "CCO", "CCT", "RES", "GOV", "EDU", "ASS", "HOS", "PRT", "TDM",
                            "TRD", "PLT", "LAM", "TRS", "ABO", "INB", "LGR", "OMK", "MAJ"
                        ],
                        "Ispapi-Name" => "X-CA-LEGALTYPE"
                    ])
                ],
                ".it" => [
                    [
                        "Name" => "PIN",
                        "Type" => "text",
                        "Ispapi-Name" => "X-IT-PIN"
                    ]
                ],
                ".pt" => [
                    [
                        "Name" => "ROID",
                        "Type" => "text",
                        "Required" => true,
                        "Ispapi-Name" => "X-PT-ROID"
                    ]
                ]
            ]
        ];
        // matching configuration for tlds
        self::$additionalfieldscfg[self::$entity]["register"][".asn.au"] = self::$additionalfieldscfg[self::$entity]["register"][".com.au"];
        self::$additionalfieldscfg[self::$entity]["register"][".id.au"] = self::$additionalfieldscfg[self::$entity]["register"][".com.au"];
        self::$additionalfieldscfg[self::$entity]["register"][".net.au"] = self::$additionalfieldscfg[self::$entity]["register"][".net.au"];
        self::$additionalfieldscfg[self::$entity]["register"][".org.au"] = self::$additionalfieldscfg[self::$entity]["register"][".net.au"];
        self::$additionalfieldscfg[self::$entity]["register"][".pm"] = self::$additionalfieldscfg[self::$entity]["register"][".fr"];
        self::$additionalfieldscfg[self::$entity]["register"][".re"] = self::$additionalfieldscfg[self::$entity]["register"][".fr"];
        self::$additionalfieldscfg[self::$entity]["register"][".tf"] = self::$additionalfieldscfg[self::$entity]["register"][".fr"];
        self::$additionalfieldscfg[self::$entity]["register"][".wf"] = self::$additionalfieldscfg[self::$entity]["register"][".fr"];
        self::$additionalfieldscfg[self::$entity]["register"][".yt"] = self::$additionalfieldscfg[self::$entity]["register"][".fr"];
        self::$additionalfieldscfg[self::$entity]["register"][".рф"] = self::$additionalfieldscfg[self::$entity]["register"][".ru"];
        self::$additionalfieldscfg[self::$entity]["register"][".香港"] = self::$additionalfieldscfg[self::$entity]["register"][".hk"];

        // matching configuration for type register and transfer
        foreach ([
            ".abogado", ".ae", ".attorney", ".broker", ".cfd", ".de", ".dentist", ".eco", ".eu", ".forex", ".health",
            ".law", ".lawyer", ".lt", ".makeup", ".nu", ".ro", ".sg", ".spreadbetting", ".trading", ".us"
        ] as $tld) {
            self::$additionalfieldscfg[self::$entity]["transfer"][$tld] = self::$additionalfieldscfg[self::$entity]["register"][$tld];
        }

        // add translation support in case no generic LangVar field got configured
        foreach (self::$additionalfieldscfg[self::$entity] as $type => &$tlds) {
            foreach ($tlds as $tldkey => &$fields) {
                foreach ($fields as &$f) {
                    // follow a prefixed but similar way WHMCS uses for LangVar ids
                    if (!isset($f["LangVar"])) {
                        $f["LangVar"] = self::getTransKey($tldkey, $f['Name']);
                    }
                    // add translation prefix
                    // preifx is already included in Options, so care just about LangVar and Description
                    $f["LangVar"] = self::$transpfx . $f["LangVar"];
                    if (isset($f["Description"])) {// add translation prefix
                        $f["Description"] = self::$transpfx . $f["Description"];
                    }
                }
            }
        }
    }

    public static function addToCMD($params, &$command)
    {
        //TODO cleanup when _AdditionalDomainFields has $params["type"] fixed
        $params = injectDomainObjectIfNecessary($params);
        $type = isset($params["type"]) ? $params["type"] : "register";

        (new self())->setDomain($params["domainObj"]->getDomain())
                ->setDomainType($type)
                ->setFieldValues($params["additionalfields"])
                ->addToCommand($command);
    }

    public static function cleanSuffix($name)
    {
        return preg_replace("/[^a-z0-9]/i", "", $name);
    }

    public static function disableWHMCSFields($names, $fields = [])
    {
        $reversed = array_reverse($names);
        foreach ($reversed as $name) {
            array_unshift($fields, [
                "Name" => $name,
                "Remove" => true
            ]);
        }
        return $fields;
    }

    public static function getAdditionalDomainFields($tld, $type, $whmcsVersion)
    {
        $transientKey = "ispapiFields" . self::$entity . ucfirst($type) . ucfirst($tld);
        //$fields = \WHMCS\TransientData::getInstance()->retrieve($transientKey);
        //if ($fields) {
        //    $fields = json_decode($fields, true);
        //    if (isset($fields) && is_array($fields)) {
        //        return self::translate($tld, $fields, $whmcsVersion);
        //    }
        //}
        // check if a configuration exists for the given order type (register/transfer)
        $cfg = self::$additionalfieldscfg[self::$entity];
        if (!is_null($cfg) && isset($cfg[$type])) {
            // check if a configuration exists for the given tld
            $tlddotted = "." . $tld;
            if (isset($cfg[$type][$tlddotted])) {
                \WHMCS\TransientData::getInstance()->store($transientKey, json_encode($cfg[$type][$tlddotted]), 86400 * 30);
                return self::translate($tlddotted, $cfg[$type][$tlddotted], $whmcsVersion);
            }
            // check if a configuration exists for 2nd level fallback (in case of incoming 3rd level tld)
            $tldfb = preg_replace("/^[^.]+/", "", $tld);
            if ($tlddotted != $tldfb && isset($cfg[$type][$tldfb])) {
                \WHMCS\TransientData::getInstance()->store($transientKey, json_encode($cfg[$type][$tldfb]), 86400 * 30);
                return self::translate($tlddotted, $cfg[$type][$tldfb], $whmcsVersion);
            }
        }
        //nothing found ...
        return [];
    }

    public static function getAFNICFields()
    {
        return self::disableWHMCSFields(
            ["Legal Type","Info","Birthplace City","Birthplace Country","Birthplace Postcode","SIRET Number","VAT Number"],
            [
                self::getLegalTypeField("afnic", [
                    "Options" => [ "INDIV", "ORG1", "ORG2", "ORG3", "ORG4", "ASS" ]
                ]),[
                    "Name" => "VATID or SIREN/SIRET number",
                    "LangVar" => "afnictldvatid",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-LEGAL-ID",
                    "Required" => [ "Legal Type" => [ "ORG1" ] ],
                    "Description" => "afnictldvatiddescr"
                ],[
                    "Name" => "Trademark Number",
                    "LangVar" => "afnictldtrademark",
                    "Ispapi-Name" => "X-FR-REGISTRANT-TRADEMARK-NUMBER",
                    "Required" => [ "Legal Type" => [ "ORG2" ] ],
                    "Description" => "afnictldtrademarkdescr"
                ],[
                    "Name" => "DUNS Number",
                    "LangVar" => "afnictldduns",
                    "Ispapi-Name" => "X-FR-REGISTRANT-DUNS-NUMBER",
                    "Required" => [ "Legal Type" => [ "ORG3" ] ],
                    "Description" => "afnictlddunsdescr"
                ],[
                    "Name" => "Local ID",
                    "LangVar" => "afnictldlocalid",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-LOCAL-ID",
                    "Required" => [ "Legal Type" => [ "ORG4" ] ],
                    "Description" => "afnictldlocaliddescr"
                ],[
                    "Name" => "Date of Declaration [JO]",
                    "LangVar" => "afnictldjodod",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-JO-DATE-DECLARATION",
                    "Required" => [ "Legal Type" => [ "ASS" ] ],
                    "Description" => "afnictldjododdescr"
                ],[
                    "Name" => "Number [JO]",
                    "LangVar" => "afnictldjonumber",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-JO-NUMBER",
                    "Required" => [ "Legal Type" => [ "ASS" ] ],
                    "Description" => "afnictldjonumberdescr"
                ],[
                    "Name" => "Page of Announcement [JO]",
                    "LangVar" => "afnictldjopage",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-JO-PAGE",
                    "Required" => [ "Legal Type" => [ "ASS" ] ],
                    "Description" => "afnictldjopagedescr"
                ],[
                    "Name" => "Date of Publication [JO]",
                    "LangVar" => "afnictldjodop",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-JO-DATE-PUBLICATION",
                    "Required" => [ "Legal Type" => [ "ASS" ] ],
                    "Description" => "afnictldjodopdescr"
                ]
            ]
        );
    }

    public static function getAllocationTokenField($tld)
    {
        return [
            "Name" => "Registry's Allocation Token",
            "LangVar" => "allocationtoken",
            "Type" => "text",
            "Required" => true,
            "Description" => "allocationtokendescr",
            "Ispapi-Name" => "X-ALLOCATIONTOKEN"
        ];
    }

    public static function getContactIdentificationField($contacttype, $overrides = [])
    {
        return array_merge([
            "Name" => ucfirst($contacttype) . " ID number",
            "LangVar" => strtolower($contacttype) . "idnumber",
            "Type" => "text",
            "Required" => false,
            "Ispapi-Name" => "X-" . $contacttype . "-IDNUMBER"
        ], $overrides);
    }

    public static function getContactTypeField($tld, $contacttype, $overrides = [])
    {
        $f = array_merge([
            "Name" => ucfirst($contacttype) . " ID Type",
            "LangVar" => strtolower($contacttype) . "idtype",
            "Type" => "dropdown",
            "Options" => [],
            "Required" => false,
            "Ispapi-Name" => "X-" . $contacttype . "-IDTYPE"
        ], $overrides);
        $f["Options"] = self::getOptions($tld, $f["Name"], $f["Options"]);
        $f["Default"] = $f["Options"][0];
        return $f;
    }

    public static function getCountryField($overrides)
    {
        $countries = (new \WHMCS\Utility\Country())->getCountryNameArray();
        $cfg = array_merge([
            "Name" => "Registrant Citizenship",
            "Type" => "dropdown",
            "Required" => false
        ], $overrides);
        $options = [];
        if ($cfg["Options"]=="ALL") {
            $cfg["Options"] = [];
            $cfg["Options"][] = "";
            foreach ($countries as $ccode => $name) {
                $cfg["Options"][] = ($ccode . "|" . $name);
            }
        } else {
            foreach ($cfg["Options"] as &$val) {
                if ($val !== "") {
                    $val .= ("|" . (isset($countries[$val]) ? $countries[$val] : $val));
                }
            }
        }
        $cfg["Default"] = $cfg["Options"][0];
        return $cfg;
    }

    public static function getFaxFormField()
    {
        return [
            "Name" => "Fax required",
            "LangVar" => "fax",
            "Type" => "tickbox",
            "Description" => "faxdescr",
            "Default" => "",
            "Required" => true
        ];
    }

    public static function getHighlyRegulatedTLDField($tld)
    {
        return [
            "Name" => "Highly Regulated TLD",
            "LangVar" => "tachighlyregulated",
            "Type" => "tickbox",
            "Required" => true,
            "Description" => self::getTACDescription($tld, "HIGHLYREGULATED"),
            "Ispapi-Name" => "X-" . self::getTLDClass($tld) . "-ACCEPT-HIGHLY-REGULATED-TAC"
        ];
    }

    public static function getIndividualRegulatedTLDField($tld, $overrides = [])
    {
        return [
            "Name" => "Terms for Individuals",
            "LangVar" => "tacagreementindiv",
            "Type" => "tickbox",
            "Description" => self::getTACDescription($tld, "INDIVIDUALREGULATED"),
            "Required" => true,
            "LangVar" => "tacagreement",
            "Ispapi-Name" => "X-" . self::getTLDClass($tld) . "-ACCEPT-INDIVIDUAL-REGISTRATION-TAC"
        ];
    }

    public static function getIntendedUseField($tld = "", $overrides = [])
    {
        $cfg = array_merge([
            "Name" => "Intended Use",
            "LangVar" => "intendeduse",
            "Type" => "text",
            "Required" => true,
            "Ispapi-Name" => "X-CORE-INTENDED-USE"
        ], $overrides);
        if ($cfg["Type"] == "dropdown") {
            $f["Options"] = self::getOptions($tld, $f["Name"], $f["Options"]);
            $f["Default"] = $f["Options"][0];
        }
        return $cfg;
    }

    public static function getLanguageField($overrides = [])
    {
        $langs = [];
        foreach (\Lang::getLocales() as $row) {
            $langs[$row["languageCode"]] = $row["localisedName"];
        }
        $cfg = array_merge([
            "Name" => "Registrant Language",
            "Type" => "dropdown",
            "Required" => false
        ], $overrides);
        
        $options = [];
        foreach ($cfg["Options"] as &$val) {
            if ($val !== "") {
                $lc = strtolower($val);
                $val .= ("|" . (isset($langs[$lc]) ? $langs[$lc] : $val));
            }
        }
        $cfg["Default"] = $cfg["Options"][0];
        return $cfg;
    }

    public static function getLegalTypeField($tld, $overrides = [])
    {
        $f = array_merge([
            "Name" => "Legal Type",
            "Type" => "dropdown",
            "LangVar" => "legaltype",
            "Options" => ["INDIV", "ORG"]
        ], $overrides);

        $f["Options"] = self::getOptions($tld, $f["Name"], $f["Options"]);
        $f["Default"] = $f["Options"][0];
        return $f;
    }

    public static function getNexusCategoryField($tld, $overrides = [])
    {
        $cfg = array_merge([
            "Name" => "Nexus Category",
            "LangVar" => "nexuscategory",
            "Type" => "dropdown",
            "Required" => true,
            "Options" => [],
            "Ispapi-Name" => "X-" . self::getTLDClass($tld) . "-NEXUS-CATEGORY"
        ], $overrides);
        $cfg["Options"] = self::getOptions($tld, $cfg["Name"], $cfg["Options"]);
        $cfg["Default"] = explode(",", $cfg["Options"])[0];
        return $cfg;
    }

    public static function getOptions($tld, $transprefix, $optvals)
    {
        $options = [];
        foreach ($optvals as &$val) {
            if ($val !== "") {
                $val .= ("|" . self::$transpfx . self::getTransKey($tld, $transprefix . strtolower($val)));
            }
            $options[] = $val;
        }
        return $options;
    }

    public static function getRegistrantIdentificationField($tld, $overrides = [])
    {
        $tclass = self::getTLDClass($tld);
        return array_merge(
            self::getContactIdentificationField("REGISTRANT"),
            [
                "Required" => true,
                "Ispapi-Name" =>  "X-" . $tclass . "-REGISTRANT-IDNUMBER"
            ],
            $overrides
        );
    }

    public static function getRegulatedTLDField($tld, $overrides = [], $descrid = "")
    {
        return array_merge([
            "Name" => "Agreement",
            "Type" => "tickbox",
            "Description" => self::getTACDescription($tld, "REGULATED", $descrid),
            "Required" => true,
            "LangVar" => "tacagreement",
            "Ispapi-Name" => "X-" . self::getTLDClass($tld) . "-ACCEPT-REGISTRATION-TAC"
        ], $overrides);
    }

    public static function getTAC($tld)
    {
        $tac = [
            ".abogado" => "http://nic.law/eligibilitycriteria/",
            ".ae" => "https://www.nic.ae/content.jsp?action=termcond_ae",
            ".bank" => "https://www.register.bank/get-started/",
            ".boats" => "https://get.boats/policies/",
            ".broker" => "https://nic.broker/",
            ".cat" => "http://domini.cat/en/domini/rules-cat-domain",
            ".cfd" => "https://nic.cfd/",
            ".eco" => "https://home.eco/registrars/policies/",
            ".es" => "https://www.dominios.es/dominios/en/todo-lo-que-necesitas-saber/sobre-registros-de-dominios/terms-and-conditions",
            ".fi" => "https://domain.fi/info/en/index/hakeminen/kukavoihakea.html",
            ".forex" => "https://nic.forex/",
            ".gay" => "https://toplevel.design/policy",
            ".health" => "https://get.health/registration-policies",
            ".hk" => "https://www.hkirc.hk/content.jsp?id=3#!/6",
            ".homes" => "https://domains.homes/Policies/",
            ".id" => "https://pandi.id/regulasi/",
            ".insurance" => "https://www.register.insurance/get-started/",
            ".it" => "https://www.nic.it/sites/default/files/documenti/2019/Synchronous_Technical_Guidelines_v2.5.pdf",
            ".law" => "http://nic.law/eligibilitycriteria/",
            ".markets" => "https://nic.markets/",
            ".ngo" => "https://thenew.org/org-people/about-pir/policies/ngo-ong-policies/",
            ".no" => "https://www" . (self::$isOTE ? "-ote" : "" ) . ".domainform.net/form/no/search?view=registration",
            ".nu" => "https://internetstiftelsen.se/app/uploads/2019/02/terms-and-conditions-nu.pdf",
            ".nyc" => "https://www.ownit.nyc/policies/",
            ".paris" => "http://bienvenue.paris/registry-policies-paris/",
            ".ru" => "http://www.cctld.ru/en/docs/rules.php",
            ".se" => "https://internetstiftelsen.se/app/uploads/2019/02/registreringsvillkor-se-eng.pdf",
            ".spreadbetting" => "https://nic.spreadbetting/",
            ".trading" => "https://nic.trading/",
            ".xxx" => "http://www.icmregistry.com/about/policies/registry-registrant-agreement/",
            ".za" => "https://www.zadna.org.za/"
        ];
        return isset($tac[$tld]) ? $tac[$tld] : "#";
    }

    public static function getTACDescription($tld, $type = "REGULATED", $descrid = false)
    {
        $tac = self::getTAC($tld);
        $map = [
            "HIGHLYREGULATED" => [
                "default" => "tachighlyregulateddescrdefault",
                "notac" => "tachighlyregulateddescrnotac",
                ".eco" => "tachighlyregulateddescreco"
            ],
            "INDIVIDUALREGULATED" => [
                "default" => "tacindividualregulateddescrdefault"
            ],
            "REGULATED" => [
                "default" => "tacregulateddescrdefault",
                ".ngo" => "tacregulateddescrngo",
                ".it" => [
                    "section3" => "tacregulateddescritsection3",
                    "section5" => "tacregulateddescritsection5",
                    "section6" => "tacregulateddescritsection6",
                    "section7" => "tacregulateddescritsection7"
                ]
            ]
        ];

        if (isset($map[$type][$tld])) {
            if ($descrid && isset($map[$type][$tld][$descrid])) {
                return $map[$type][$tld][$descrid];
            }
            return $map[$type][$tld];
        }
        if ($tac == "#" && isset($map[$type]["notac"])) {
            return $map[$type]["notac"];
        }
        return $map[$type]["default"];
    }

    public static function getTLDClass($tld)
    {
        return strtoupper(preg_replace("/\./", "", $tld, 1));
    }

    public static function getTransKey($tld, $suffix)
    {
        return strtolower(str_replace(".", "", $tld) . "tld" . self::cleanSuffix($suffix));
    }

    public static function getVATIDField($tld, $contacttype, $overrides = [])
    {
        return array_merge([
            "Name" => ucfirst($contacttype) . " VAT ID",
            "LangVar" => strtolower($contacttype) . "vatid",
            "Type" => "text",
            'Required' => [ 'Legal Type' => [ 'ORG' ] ],
            "Ispapi-Name" => "X-" . $contacttype . "-VATID"
        ], $overrides);
    }

    public static function getYesNoField($tld, $overrides = [], $customlables = false)
    {
        $cfg = array_merge([
            "Type" => "dropdown",
            "Options" => ["no", "yes"]
        ], $overrides);
        if (!$customlables) {
            $cfg["Options"] = self::getOptions($tld, "yesno", $cfg["Options"]);
        } else {
            $cfg["Options"] = self::getOptions($tld, $cfg["Name"], $cfg["Options"]);
        }
        $cfg["Default"] = $cfg["Options"][0];
        return $cfg;
    }

    public static function translate($tld, $fields, $whmcsVersion)
    {
        foreach ($fields as &$f) {
            // translate Description field
            if (isset($f["Description"])) {
                $f["Description"] = \Lang::trans($f["Description"]);
                if (preg_match("/####TAC####/", $f["Description"])) {
                    $tac = self::getTAC($tld);
                    $f["Description"] = preg_replace("/####TAC####/", $tac, $f["Description"]);
                }
                if (preg_match("/####TLD####/", $f["Description"])) {
                    $f["Description"] = preg_replace("/####TLD####/", strtoupper($tld), $f["Description"]);
                }
            }
            // translate Options field
            if (isset($f["Options"])) {
                foreach ($f["Options"] as $idx => $opt) {
                    if (preg_match("/\|/", $opt)) {
                        list($val, $transkey) = explode("|", $opt);
                        $f["Options"][$idx] = ($val . "|" . \Lang::trans($transkey));
                    }
                }
                $f["Options"] = implode(",", $f["Options"]);
            }
            // Make conditional Requirements downward compatible
            if ($f["Required"] && is_array($f["Required"])) {
                if (substr($whmcsVersion, 0, 3) < "7.9") {
                    $f["Required"] = false;
                }
            }
        }
        // return in expected WHMCS format
        return ["fields" => $fields];
    }


    /**
     * @param array $command API command to add additional domain field parameters to
     */
    public function addToCommand(&$command)
    {
        foreach ($this->getFields() as $fieldKey => $values) {
            $remove = $this->getConfigValue($fieldKey, "Ispapi-CmdRemove");
            if (!empty($remove)) {
                if (isset($remove[$this->getFieldValue($fieldKey)])) {
                    $val = $remove[$this->getFieldValue($fieldKey)];
                    if (is_array($val)) {
                        foreach ($val as $k => $v) {
                            unset($command[$k][$v]);
                        }
                    } else {
                        unset($command[$remove[$this->getFieldValue($fieldKey)]]);
                    }
                }
            }
            $iname = $this->getConfigValue($fieldKey, "Ispapi-Name");
            if (empty($iname)) {
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
            if (!empty($val)) {
                $command[$iname] = $val;
            }
        }
    }

    /**
     * Pre-fill WHMCS additional field values by our API data
     * @param array $r API response
     * untested! TODO
     */
    public function setFieldValuesFromAPI($r)
    {
        if ($r["CODE"] == "200") {
            $data = [];
            //check if $r["PROPERTY"] has been used for this fn call
            if (isset($r["PROPERTY"])) {
                $r = $r["PROPERTY"];
            }
            foreach ($this->getFields() as $fieldKey => $values) {
                $type = $this->getConfigValue($fieldKey, "Type");
                $iname = $this->getConfigValue($fieldKey, "Ispapi-Name");
                $name = $this->getConfigValue($fieldKey, "Name");
                $defaultval = $this->getConfigValue($fieldKey, "Default");
                if (isset($r[$iname][0])) {
                    $data[$name] = $r[$iname][0];
                    if ($type == "tickbox" && $data[$name] == "1") {
                        $data[$name] = $defaultval;
                    }
                }
            }
            parent::setFieldValues($data);
        }
        return $this;
    }
}
