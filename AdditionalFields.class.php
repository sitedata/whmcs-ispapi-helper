<?php

namespace ISPAPI;

class AdditionalFields extends \WHMCS\Domains\AdditionalFields
{
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
                    [ 'Name' => '.AERO ID', 'Ispapi-Name' => "X-AERO-ENS-AUTH-ID" ],
                    [ 'Name' => '.AERO Key', 'Ispapi-Name' => "X-AERO-ENS-AUTH-KEY", 'Required' => true ]
                ],
                ".asia" => self::disableWHMCSFields(["Legal Type", "Identity Form", "Identity Number"]),
                ".attorney" => [ self::getHighlyRegulatedTLDField(".attorney") ],
                ".bank" => [self::getAllocationTokenField(".bank")],
                ".insurance" => [self::getAllocationTokenField(".insurance")],
                ".barcelona" => [self::getIntendedUseField()],
                ".cat" => [self::getHighlyRegulatedTLDField(".cat"), self::getIntendedUseField()],
                ".boats" => [ self::getHighlyRegulatedTLDField(".boats") ],
                ".broker" => [ self::getHighlyRegulatedTLDField(".broker") ],
                ".ca" => self::disableWHMCSFields('CIRA Agreement', [
                    self::getLegalTypeField([
                        "Options" => implode(",", [
                            "CCO|Corporation",
                            "CCT|Canadian Citizen",
                            "RES|Permanent Resident of Canada",
                            "GOV|Government or government entity in Canada",
                            "EDU|Canadian Educational Institution",
                            "ASS|Canadian Unincorporated Association",
                            "HOS|Canadian Hospital",
                            "PRT|Partnership Registered in Canada",
                            "TDM|Trade-mark registered in Canada (by a non-Canadian owner)",
                            "TRD|Canadian Trade Union",
                            "PLT|Canadian Political Party",
                            "LAM|Canadian Library Archive or Museum",
                            "TRS|Trust established in Canada",
                            "ABO|Aboriginal Peoples (individuals or groups) indigenous to Canada",
                            "INB|Indian Band recognized by the Indian Act of Canada",
                            "LGR|Legal Representative of a Canadian Citizen or Permanent Resident",
                            "OMK|Official mark registered in Canada",
                            "MAJ|Her Majesty the Queen"
                        ]),
                        "Ispapi-Name" => "X-CA-LEGALTYPE"
                    ]),[
                        "Name" => "Contact Language",
                        "Type" => "dropdown",
                        "Options" => "EN|English,FR|French",
                        "Default" => "EN|English",
                        "Required" => true,
                        "Ispapi-Name" => "X-CA-LANGUAGE"
                    ],[
                        "Name" => "WHOIS Opt-out",
                        "LangVar" => "hxflagswhoisoptout",
                        "Ispapi-Name" => "X-CA-DISCLOSE"
                    ]
                ]),
                ".cfd" => [ self::getHighlyRegulatedTLDField(".cfd") ],
                ".cn" => self::disableWHMCSFields(["cnhosting", "cnhregisterclause"], [
                    self::getContactIdentificationField("REGISTRANT", [
                        "Required" => true,
                        "Ispapi-Name" => "X-CN-REGISTRANT-ID-NUMBER"
                    ]),
                    self::getContactTypeField("REGISTRANT", [
                        "Options" => implode(",", [
                            "SFZ|Chinese ID card",
                            "HZ|Foreign passport",
                            "GAJMTX|Exit-Entry Permit for Travelling to and from Hong Kong and Macao",
                            "TWJMTX|Travel passes for Taiwan Residents to Enter or Leave the Mainland",
                            "WJLSFZ|Foreign Permanent Resident ID Card",
                            "GAJZZ|Residence permit for Hong Kong, Macao residents",
                            "TWJZZ|Residence permit for Taiwan residents",
                            "JGZ|Chinese officer certificate",
                            "ORG|Chinese Organization Code Certificate",
                            "YYZZ|Chinese business license",
                            "TYDM|Certificate for Uniform Social Credit Code",
                            "BDDM|Military Code Designation",
                            "JDDWFW|Military Paid External Service License",
                            "SYDWFR|Public Institution Legal Person Certificate",
                            "WGCZJG|Resident Representative Offices of Foreign Enterprises Registration Form",
                            "SHTTFR|Social Organization Legal Person Registration Certificate",
                            "ZJCS|Religion Activity Site Registration Certificate",
                            "MBFQY|Private Non-Enterprise Entity Registration Certificate",
                            "JJHFR|Fund Legal Person Registration Certificate",
                            "LSZY|Practicing License of Law Firm",
                            "WGZHWH|Registration Certificate of Foreign Cultural Center in China",
                            "WLCZJG|Resident Representative Office of Tourism Departments of Foreign Government Approval Registration Certificate",
                            "SFJD|Judicial Expertise License",
                            "JWJG|Overseas Organization Certificate",
                            "SHFWJG|Social Service Agency Registration Certificate",
                            "MBXXBX|Private School Permit",
                            "YLJGZY|Medical Institution Practicing License",
                            "GZJGZY|Notary Organization Practicing License",
                            "BJWSXX|Beijing School for Children of Foreign Embassy Staff in China Permit",
                            "QT|Others"
                        ]),
                        "Ispapi-Name" => "X-CN-REGISTRANT-ID-TYPE"
                    ])
                ]),
                ".com.br" => [
                    [
                        "Name" => "Identification Number",
                        "LangVar" => "hxflagsidentificationnumber",
                        "Type" => "text",
                        "Required" => true,
                        "Description" => "Please provide your CPF or CNPJ numbers. These are identification numbers issued by the Department of Federal Revenue of Brazil for tax purposes",
                        "Ispapi-Name" => "X-BR-REGISTER-NUMBER"
                    ]
                ],
                ".com.au" => self::disableWHMCSFields(
                    [ "Registrant Name", "Eligibility Name", "Eligibility ID", "Eligibility ID Type", "Eligibility Type", "Eligibility Reason"],
                    [
                        self::getContactIdentificationField("REGISTRANT", [
                            "Required" => true,
                            "Ispapi-Name" => "X-CN-REGISTRANT-ID-NUMBER"
                        ]),
                        self::getContactTypeField("REGISTRANT", [
                            "Required" => true,
                            "Options" =>  implode(",", [
                                "ABN|Australian Business Number",
                                "ACN|Australian Company Number",
                                "RBN|Business Registration Number",
                                "TM|Trademark Number"
                            ])
                        ])
                    ]
                ),
                ".de" => self::disableWHMCSFields([ "Tax ID", "Address Confirmation", "Agree to DE Terms"], [
                    [
                        "Name" => "General Request Contact",
                        "Type" => "text",
                        "Description" => "The registry will identify this as the general request contact information. You can provide an email address or a website url",
                        "Required" => false,
                        "Ispapi-Name" => "X-DE-GENERAL-REQUEST"
                    ], [
                        "Name" => "Abuse Team Contact",
                        "Type" => "text",
                        "Description" => "The registry will identify this as the abuse team contact information. You can provide an email address or a website url",
                        "Required" => false,
                        "Ispapi-Name" => "X-DE-ABUSE-CONTACT"
                    ]
                ]),
                ".dentist" => [ self::getHighlyRegulatedTLDField(".dentist") ],
                ".dk" => [
                    self::getLegalTypeField([
                        "Options" => "Individual,Organization",
                        "Description" => "(Also choose `Individual` in case you're a company without VATID)"
                    ]),
                    self::getVATIDField(".dk", "REGISTRANT"),
                    [
                        "Name" => "Registrant Contact",
                        "LangVar" => "hxflagsdktldregistrantcontact",
                        "Type" => "text",
                        "Required" => false,
                        "Description" => "(DK-HOSTMASTER User ID)",
                        "Ispapi-Name" => "X-DK-REGISTRANT-CONTACT"
                    ],
                    self::getVATIDField(".dk", "ADMIN"),
                    [
                        "Name" => "Admin Contact",
                        "LangVar" => "hxflagsdktldadmincontact",
                        "Type" => "text",
                        "Required" => false,
                        "Description" => "(DK-HOSTMASTER User ID)",
                        "Ispapi-Name" => "X-DK-ADMIN-CONTACT"
                    ]
                ],
                ".eco" => [ self::getHighlyRegulatedTLDField(".eco") ],
                ".es" => self::disableWHMCSFields(
                    [ "ID Form Type", "ID Form Number", 'Entity Type'],
                    [
                    self::getIndividualRegulatedTLDField(".es"),
                    [
                        "Name" => "Registrant Type",
                        "Type" => "dropdown",
                        "Options" => implode(",", [
                            "0|Otra; For non-spanish owner",
                            "1|NIF/NIE; For Spanish Individual or Organization",
                            "3|Alien registration card"
                        ]),
                        "Default" => "0|Otra; For non-spanish owner",
                        "Required" => true,
                        "Ispapi-Name" => "X-ES-REGISTRANT-TIPO-IDENTIFICACION"
                    ],[
                        "Name" => "Registrant Identification Number",
                        "Type" => "text",
                        "Required" => true,
                        "Ispapi-Name" => "X-ES-REGISTRANT-IDENTIFICACION"
                    ],[
                        "Name" => "Admin Type",
                        "Type" => "dropdown",
                        "Options" => implode(",", [
                            "0|Otra; For non-spanish owner",
                            "1|NIF/NIE; For Spanish Individual or Organization",
                            "3|Alien registration card"
                        ]),
                        "Default" => "0|Otra; For non-spanish owner",
                        "Required" => true,
                        "Ispapi-Name" => "X-ES-ADMIN-TIPO-IDENTIFICACION"
                    ],[
                        "Name" => "Admin Identification Number",
                        "Type" => "text",
                        "Required" => true,
                        "Ispapi-Name" => "X-ES-ADMIN-IDENTIFICACION"
                    ],
                    self::getLegalTypeField([
                        "Options" => implode(",", [
                            "",
                            "1|Individual",
                            "39|Economic Interest Group",
                            "47|Association",
                            "59|Sports Association",
                            "68|Professional Association",
                            "124|Savings Bank",
                            "150|Community Property",
                            "152|Community of Owners",
                            "164|Order or Religious Institution",
                            "181|Consulate",
                            "197|Public Law Association",
                            "203|Embassy",
                            "229|Local Authority",
                            "269|Sports Federation",
                            "286|Foundation",
                            "365|Mutual Insurance Company",
                            "434|Regional Government Body",
                            "436|Central Government Body",
                            "439|Political Party",
                            "476|Trade Union",
                            "510|Farm Partnership",
                            "524|Public Limited Company",
                            "525|Sports Association",
                            "554|Civil Society",
                            "560|General Partnership",
                            "562|General and Limited Partnership",
                            "566|Cooperative",
                            "608|Worker-owned Company",
                            "612|Limited Company",
                            "713|Spanish Office",
                            "717|Temporary Alliance of Enterprises",
                            "744|Worker-owned Limited Company",
                            "745|Regional Public Entity",
                            "746|National Public Entity",
                            "747|Local Public Entity",
                            "878|Designation of Origin Supervisory Council",
                            "879|Entity Managing Natural Areas",
                            "877|Others"
                        ]),
                        "Required" => false,
                        "Ispapi-Name" => "X-ES-REGISTRANT-FORM-JURIDICA"
                    ])
                    ]
                ),
                ".eu" => self::disableWHMCSFields([ 'Entity Type' ], [
                    [
                        "Name" => "Registrant Citizenship",
                        "Options" => implode(",", ["", "AT", "BE", "BG", "CZ", "CY", "DE", "DK", "ES", "EE", "FI", "FR", "GR", "HU", "IE", "IT", "LT", "LU", "LV", "MT", "NL", "PL", "PT", "RO", "SE", "SK", "SI", "HR"]),
                        "Default" => "",
                        "Description" => "Required only if you're a European Citizen residing outside of the EU",
                        "Ispapi-Name" => "X-EU-REGISTRANT-CITIZENSHIP",
                        "Type" => "dropdown",
                        "Required" => false
                    ]
                ]),
                ".fi" => [
                    self::getRegulatedTLDField(".fi"),
                    self::getRegistrantIdentificationField(".fi", [
                        "Required" => false,
                        "Description" => (
                            "<ul><li>Companies: Please provide the registernumber.</li>" .
                            "<li>Individuals from Finland: provide the identity number.</li>" .
                            "<li>Other Individuals: leave empty.</li></ul>" .
                            "For individuals, please note that the X-FI-REGISTRANT-IDNUMBER has to contain of eleven characters of the form DDMMYYCZZZQ, " .
                            "where DDMMYY is the date of birth, C the century sign, ZZZ the individual number and Q the control character (checksum). The " .
                            "sign for the century is either + (1800–1899), - (1900–1999), or A (2000–2099). The individual number ZZZ is odd for males and " .
                            "even for females and for people born in Finland its range is 002-899 (larger numbers may be used in special cases). An example " .
                            "of a valid code is 311280-888Y."
                        )
                    ]),
                    [
                        "Name"  => "Registrant Birthdate",
                        "LangVar" => "hxflagsregistrantbirthdate",
                        "Type"  => "text",
                        "Default" => "",
                        "Description" => "(YYYY-MM-DD; only required for Individuals not from Finland)"
                    ]
                ],
                ".forex" => [ self::getHighlyRegulatedTLDField(".forex") ],
                ".fr" => self::getAFNICFields(),
                ".health" => [ self::getHighlyRegulatedTLDField(".health") ],
                ".hk" => self::disableWHMCSFields(
                    [
                        "Registrant Type", 'Organizations Name in Chinese', 'Organizations Supporting Documentation',
                        'Organizations Document Number', 'Organizations Issuing Country', 'Organizations Industry Type',
                        'Individuals Supporting Documentation', 'Individuals Document Number', 'Individuals Issuing Country',
                        'Individuals Under 18'
                    ],
                    [
                        self::getIndivualRegulatedTLDField(".hk", [
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
                            "Options" => implode(",", [
                                "HKID|Individual - Hong Kong Identity Number",
                                "OTHID|Individual - Other's Country Identity Number",
                                "PASSNO|Individual - Passport No.",
                                "BIRTHCERT|Individual - Birth Certificate",
                                "OTHIDV|Individual - Others Individual Document",
                                "BR|Organization - Business Registration Certificate",
                                "CI|Organization - Certificate of Incorporation",
                                "CRS|Organization - Certificate of Registration of a School",
                                "HKSARG|Organization - Hong Kong Special Administrative Region Government Department",
                                "HKORDINANCE|Organization - Ordinance of Hong Kong",
                                "OTHORG|Organization - Others Organization Document"
                            ]),
                            "Default" => "HKID|Individual - Hong Kong Identity Number",
                            "Description" => (
                                "(NOTE: Additionally, you may need to send us a copy of the document via email. For .HK, this step is only required " .
                                "upon request by the registry. For .COM.HK, a copy of a business certificate is required before we can process the registration.)"
                            ),
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
                            "Description" => "(required for Registrant Document Types `Others Individual/Organization Document`)",
                            "Ispapi-Name" => "X-HK-REGISTRANT-OTHER-DOCUMENT-TYPE"
                        ],[
                            "Name" => "Registrant Document Number",
                            "Type" => "text",
                            "Required" => true,
                            "Ispapi-Name" => "X-HK-REGISTRANT-DOCUMENT-NUMBER"
                        ],[
                            "Name" => "Registrant Document Origin Country",
                            "Type" => "text",
                            "Required" => true,
                            "Description" => "(two-letter country code in format <a href='https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2' target='_blank'>ISO 3166-1 alpha-2</a>)",
                            "Ispapi-Name" => "X-HK-REGISTRANT-DOCUMENT-ORIGIN-COUNTRY"
                        ],[
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
                            "Description" => "(mandatory for individuals, format YYYY-MM-DD)",
                            "Ispapi-Name" => "X-HK-REGISTRANT-BIRTH-DATE"
                        ]
                    ]
                ),
                ".homes" => [ self::getHighlyRegulatedTLDField(".homes") ],
                ".id" => [ self::getHighlyRegulatedTLDField(".id") ],
                ".ie" => [
                    [
                        "Name" => "Registrant Class",
                        "Type" => "dropdown",
                        "Options" => implode(",", [
                            "Company",
                            "Business Owner",
                            "Club/Band/Local Group",
                            "School/College",
                            "State Agency",
                            "Charity",
                            "Blogger/Other"
                        ]),
                        "Default" => "Company",
                        "Description" => "",
                        "Required" => true,
                        "Ispapi-Name" => "X-IE-REGISTRANT-CLASS"
                    ], [
                        "Name" => "Proof of connection to Ireland",
                        "Type" => "text",
                        "Description" => (
                            "Provide any information supporting your registration request, such as proof of eligibility (e.g. ".
                            "VAT, RBN, CRO, CHY, NIC, or Trademark number; school roll number; link to social media page) or a ".
                            "brief explanation of why you want this domain and what you will use it for."
                        ),
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
                ".it" => self::disableWHMCSFields([ "Legal Type", "Tax ID", "Publish Personal Data" ], [
                    [
                        "Name" => "Accept Section 3",
                        "Description" => (
                            "<b>Section 3 - Declarations and assumptions of liability</b><br/><div style='text-align:justify;margin-bottom:10px;'>" .
                            "The Registrant of the domain name in question, declares under their own responsibility that they are:" .
                            "<ul><li>in possession of the citizenship or resident in a country belonging to the European Union (in the case of registration for natural persons);</li>" .
                            "<li>established in a country belonging to the European Union (in the case of registration for other organizations);</li>" .
                            "<li>aware and accept that the registration and management of a domain name is subject to the 'Rules of assignment and management of domain names in ccTLD. it' and 'Regulations for the resolution of disputes in the ccTLD.it' and their subsequent amendments;</li>" .
                            "<li>entitled to the use and/or legal availability of the domain name applied for, and that they do not prejudice, with the request for registration, the rights of others;</li>" .
                            "<li>aware that for the inclusion of personal data in the Database of assigned domain names, and their possible dissemination and accessibility via the Internet, consent must be given explicitly by ticking the appropriate boxes in the information below. See 'The policy of the .it Registry in the Whois Database' on the website of the Registry (http://www.nic.it);</li>" .
                            "<li>aware and agree that in the case of erroneous or false declarations in this request, the Registry shall immediately revoke the domain name, or proceed with other legal actions. In such case the revocation shall not in any way give rise to claims against the Registry;</li>" .
                            "<li>release the Registry from any liability resulting from the assignment and use of the domain name by the natural person that has made the request;</li>" .
                            "<li>accept Italian jurisdiction and laws of the Italian State.</li></ul></div>"
                        ),
                        "Type" => "tickbox",
                        "Ispapi-Name" => "X-IT-ACCEPT-LIABILITY-TAC",
                        "Required" => true
                    ],[
                        "Name" => "Accept Section 5",
                        "Description" => (
                            "<b>Section 5 - Consent to the processing of personal data for registration</b><br/><div style='text-align:justify;margin-bottom:10px;'>" .
                            "The interested party, after reading the above disclosure, gives consent to the processing of information required for registration, as defined " .
                            "in the above disclosure. Giving consent is optional, but if no consent is given, it will not be possible to finalize the registration, assignment and management of the domain name.</div>"
                        ),
                        "Type" => "tickbox",
                        "Ispapi-Name" => "X-IT-ACCEPT-REGISTRATION-TAC"
                    ],[
                        "Name" => "Accept Section 6",
                        "Description" => (
                            "<b>Section 6 - Consent to the processing of personal data for diffusion and accessibility via the Internet</b><br/><div style='text-align:justify;margin-bottom:10px;'>" .
                            "The interested party, after reading the above disclosure, gives consent to the dissemination and accessibility via the Internet, as defined in the disclosure above. " .
                            "Giving consent is optional, but absence of consent does not allow the dissemination and accessibility of Internet data.</div>"
                        ),
                        "Type" => "tickbox",
                        "Ispapi-Name" => "X-IT-ACCEPT-DIFFUSION-AND-ACCESSIBILITY-TAC",
                        "Required" => true
                    ],[
                        "Name" => "Accept Section 7",
                        "Description" => (
                            "<b>Section 7 - Explicit Acceptance of the following points</b><br/><div style='text-align:justify;margin-bottom:10px;'>" .
                            "For explicit acceptance, the interested party declares that they:" .
                            "<ul><li>d) are aware and agree that the registration and management of a domain name is subject to the 'Rules of assignment and management of domain names in ccTLD.it' and 'Regulations for the resolution of disputes in the ccTLD.it' and their subsequent amendments;</li>" .
                            "<li>e) are aware and agree that in the case of erroneous or false declarations in this request, the Registry shall immediately revoke the domain name, or proceed with other legal actions. In such case the revocation shall not in any way give rise to claims against the Registry;</li>" .
                            "<li>f) release the Registry from any liability resulting from the assignment and use of the domain name by the natural person that has made the request;</li>" .
                            "<li>g) accept the Italian jurisdiction and laws of the Italian State.</li></ul></div>"
                        ),
                        "Type" => "tickbox",
                        "Ispapi-Name" => "X-IT-ACCEPT-EXPLICIT-TAC",
                        "Required" => true
                    ],[
                        "Name" => "PIN",
                        "Type" => "text",
                        "Ispapi-Name" => "X-IT-PIN"
                    ]
                ]),
                ".jobs" => [
                    [
                        "Name" => "Website",
                        "Ispapi-Name" => "X-JOBS-COMPANYURL"
                    ],[
                        "Name" => "Industry Classification",
                        "Type" => "dropdown",
                        "Options" => implode(",", [
                            "2|Accounting/Banking/Finance",
                            "3|Agriculture/Farming",
                            "21|Biotechnology/Science",
                            "5|Computer/Information Technology",
                            "4|Construction/Building Services",
                            "12|Consulting",
                            "6|Education/Training/Library",
                            "7|Entertainment",
                            "13|Environmental",
                            "19|Hospitality",
                            "10|Government/Civil Service",
                            "11|Healthcare",
                            "15|HR/Recruiting",
                            "16|Insurance",
                            "17|Legal",
                            "18|Manufacturing",
                            "20|Media/Advertising",
                            "9|Parks & Recreation",
                            "26|Pharmaceutical",
                            "22|Real Estate",
                            "14|Restaurant/Food Service",
                            "23|Retail",
                            "8|Telemarketing",
                            "24|Transportation",
                            "25|Other"
                        ]),
                        "Default" => "2|Accounting/Banking/Finance",
                        "Required" => true,
                        "Ispapi-Name" => "X-JOBS-INDUSTRYCLASSIFICATION"
                    ],[
                        "Name" => "Member of a HR Association",
                        "Type" => "dropdown",
                        "Options" => "no|No,yes|Yes",
                        "Default" => "no|No",
                        "Ispapi-Name" => "X-JOBS-HRANAME"
                    ],[
                        "Name" => "Contact Job Title",
                        "Type" => "text",
                        "Required" => true,
                        "Ispapi-Name" => "X-JOBS-TITLE"
                    ],[
                        "Name" => "Contact Type",
                        "Type" => "dropdown",
                        "Options" => "1|Administrative,0|Other",
                        "Default" => "1|Administrative",
                        "Ispapi-Name" => "X-JOBS-ADMINTYPE"
                    ]
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
                ".lawyer" => [ self::getHighlyRegulatedTLDField(".lawyer") ],
                ".lt" => [
                    [
                        "Name" => "Legal Entity Identification Code",
                        "Type" => "text",
                        "Required" => false,
                        "Ispapi-Name" => "X-LT-REGISTRANT-LEGAL-ID"
                    ]
                ],
                ".madrid" => [ self::getIntendedUseField() ],
                ".makeup" => [ self::getHighlyRegulatedTLDField(".makeup") ],
                ".markets" => [ self::getHighlyRegulatedTLDField(".markets") ],
                ".melbourne" => [
                    [
                        "Name" => "Nexus Category",
                        "LangVar" => "hxflagsnexuscategory",
                        "Type" => "dropdown",
                        "Required" => true,
                        "Options" => implode(",", [
                            "A|Criteria A - Victorian Entities",
                            "B|Criteria B - Victorian Residents",
                            "C|Criteria C - Associated Entities"
                        ]),
                        "Default" => "A|Criteria A - Victorian Entities",
                        "Description" => (
                            "<div style='padding:10px 0px;text-align:justify'><b>Registration Eligibility</b><br/>In order to register or ".
                            "renew a domain name the Applicant or Registrant must satisfy one of the following Criteria A, B or C below:<br/><br/>".
                            "<b>Criteria A – Victorian Entities</b><br/>The Applicant must be an entity registered with the Australian Securities " .
                            "and Investments Commission or the Australian Business Register that:" .
                            "<ul><li>has an address in the State of Victoria associated with its ABN, ACN, RBN or ARBN; or</li><li>has a valid corporate address in the State of Victoria.</li></ul><br/>" .
                            "<b>Criteria B – Victorian Residents</b><br/>The Applicant must be an Australian citizen or resident with a valid address in the State of Victoria.<br/><br/>" .
                            "<b>Criteria C – Associated Entities</b><br/>The Applicant must be an Associated Entity. The Applicant may only apply for a domain name that is an Exact Match or Partial Match to, or an Abbreviation, or an Acronym of:" .
                            "<ul><li>the business name of the Applicant, or name by which the Applicant is commonly known ( i.e. a nickname) and the business name must be registered with the appropriate authority in the jurisdiction in which that business is domiciled; or</li>" .
                            "<li>a product that the Associated Entity manufactures or sells to entities or individuals residing in the State of Victoria;</li>".
                            "<li>a service that the Associated Entity provides to residents of the State of Victoria;</li>" .
                            "<li>an event that the Associated Entity organises or sponsors in the State of Victoria;</li>" .
                            "<li>an activity that the Associated Entity facilitates in the State of Victoria; or</li>" .
                            "<li>a course or training program that the Associated Entity provides to residents of the State of Victoria.</li></div>"
                        ),
                        "Ispapi-Name" => "X-MELBOURNE-NEXUS-CATEGORY"
                    ]
                ],
                ".mk" => [
                    self::getVATIDField(".mk", "REGISTRANT", [
                        "Required" => false
                    ]),
                    self::getContactIdentificationField("REGISTRANT")
                ],
                ".my" => [
                    [
                        "Name" => "Registrant Organisation Type",
                        "Type" => "dropdown",
                        "Options" => implode(",", [
                            "1|architect firm",
                            "2|audit firm",
                            "3|business pursuant to business registration act(rob)",
                            "4|business pursuant to commercial license ordinance",
                            "5|company pursuant to companies act(roc)",
                            "6|educational institution accredited/registered by relevant government department/agency",
                            "7|farmers organisation",
                            "8|federal government department or agency",
                            "9|foreign embassy",
                            "10|foreign office",
                            "11|government aided primary and/or secondary school",
                            "12|law firm",
                            "13|lembaga (board)",
                            "14|local authority department or agency",
                            "15|maktab rendah sains mara (mrsm) under the administration of mara",
                            "16|ministry of defences department or agency",
                            "17|offshore company",
                            "18|parents teachers association",
                            "19|polytechnic under ministry of education administration",
                            "20|private higher educational institution",
                            "21|private school",
                            "22|regional office",
                            "23|religious entity",
                            "24|representative office",
                            "25|society pursuant to societies act(ros)",
                            "26|sports organisation",
                            "27|state government department or agency",
                            "28|trade union",
                            "29|trustee",
                            "30|university under the administration of ministry of education",
                            "31|valuer, appraiser and estate agent firm"
                        ]),
                        "Default" => "1|architect firm",
                        "Required" => false,
                        "Ispapi-Name" => "X-MY-REGISTRANT-ORGANIZATION-TYPE"
                    ],
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
                    self::getFaxFormField("no/search?view=registration")
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
                    [
                        "Name" => "NEXUS Category",
                        "LangVar" => "hxflagsnexuscategory",
                        "Type" => "dropdown",
                        "Options" => implode(",", [
                            "1|Natural Person - primary domicile with physical address in NYC",
                            "2|Entity or organization - primary domicile with physical address in NYC"
                        ]),
                        "Default" => "1|Natural Person - primary domicile with physical address in NYC",
                        "Description" => "(P.O Boxes are prohibited, see <a href='http://www.ownit.nyc/policies/index.php'>.nyc Nexus Policies</a>.)",
                        "Required" => true,
                        "Ispapi-Name" => "X-NYC-REGISTRANT-NEXUS-CATEGORY"
                    ]
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
                        'Description' => "(required for EU countries AND for romanian registrants)",
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
                        [
                            "Name" => "Legal Type",
                            "LangVar" => "hxflagslegaltype",
                            "Type" => "dropdown",
                            "Options" => "Individual,Organization",
                            "Default" => "Individual"
                        ], [
                            'Name'  => 'Individuals Birthday',
                            "Description" => "",
                            "Required" => [
                                "Legal Type" => [
                                    "Individual"
                                ]
                            ],
                            "Ispapi-Name" => "X-RU-REGISTRANT-BIRTH-DATE"
                        ], [
                            "Name" => "Individuals Passport Data",
                            "Description" => "(required for individuals; including passport number, issue date, and place of issue)<br/><br/>",
                            "Type" => "text",
                            "Required" => [
                                "Legal Type" => [
                                    "Individual"
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
                        "LangVar" => "hxflagsidentificationnumber",
                        "Description" => "<div style='text-align:justify'><b>For individuals or companies located in Sweden</b> a valid Swedish personal or organizational number must be stated.<br/>
                                        <b>For individuals and companies outside of Sweden</b> the ID number (e.g. Civic registration number, company registration number, or the equivalent) must be stated.</div>",
                        "Ispapi-Name" => "X-NICSE-IDNUMBER"
                    ],[
                        "Name" => "VAT ID",
                        "LangVar" => "hxflagsregistrantvatid",
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
                        "Description" => "(must start with CHE and followed by 9 digits)",
                        "Default" => "CHE",
                        "Ispapi-Name" => "X-SWISS-REGISTRANT-ENTERPRISE-ID"
                    ],
                    self::getIntendedUseField()
                ],
                ".sydney" => [
                    [
                        "Name" => "Nexus Category",
                        "LangVar" => "hxflagsnexuscategory",
                        "Type" => "dropdown",
                        "Ispapi-Name" => "X-SYDNEY-NEXUS-CATEGORY",
                        "Options" => implode(",", [
                            "A|Criteria A - New South Wales Entities",
                            "B|Criteria B - New South Wales Residents",
                            "C|Criteria C - Associated Entities"
                        ]),
                        "Default" => "A|Criteria A - New South Wales Entities",
                        "Required" => true
                    ]
                ],
                ".tel" => [
                    [
                        "Name" => "Legal Type",
                        "LangVar" => "hxflagslegaltype",
                        "Ispapi-Name" => "X-TEL-WHOISTYPE",
                        "Options" => implode(",", [
                            "Natural|Natural Person",
                            "Legal|Legal Person"
                        ]),
                        "Default" => "Natural|Natural Person",
                        "Required" => true
                    ], [
                        "Name" => "WHOIS Opt-out",
                        "LangVar" => "hxflagswhoisoptout",
                        "Description" => "(available for Legal Type `Natural`. Choose `No` to get WHOIS data limited to registrant name.)",
                        "Type" => "dropdown",
                        "Ispapi-Name" => "X-TEL-PUBLISH",
                        "Options" => implode(",", [
                            "Y|Yes",
                            "N|No"
                        ]),
                        "Default" => "Y|Yes",
                        "Required" => false
                    ]
                ],
                ".trading" => [ self::getHighlyRegulatedTLDField(".trading") ],
                ".travel" => self::disableWHMCSFields(
                    ["Trustee Service", ".TRAVEL UIN Code", "Trustee Service Agreement ", ".TRAVEL Usage Agreement"],
                    [
                        "Name" => ".TRAVEL Industry",
                        "Description" => "(We acknowledge a relationship to the travel industry and that we are engaged in or plan to engage in activities related to travel.)",
                        "Type" => "dropdown",
                        "Ispapi-Name" => "X-TRAVEL-INDUSTRY",
                        "Default" => "1|Yes",
                        "Options" => implode(",", [
                            "1|Yes",
                            "0|No"
                        ]),
                        "Required" => true
                    ]
                ),
                ".uk" => self::disableWHMCSFields(["Legal Type", "Company ID Number", "Registrant Name", "WHOIS Opt-out"]),
                ".us" => [
                    [
                        "Name" => "Application Purpose",
                        "LangVar" => "hxflagsintendeduse",
                        "Ispapi-Name" => "X-US-NEXUS-APPPURPOSE",
                        "Options" => implode(",", [
                            "P1|Business use for profit",
                            "P2|Non-profit business",
                            "P2|Club",
                            "P2|Association",
                            "P2|Religious Organization",
                            "P3|Personal Use",
                            "P4|Educational purposes",
                            "P5|Government purposes"
                        ]),
                        "Default" => "Business use for profit",
                        "Required" => true
                    ], [
                        "Name" => "Nexus Category",
                        "LangVar" => "hxflagsnexuscategory",
                        "Description" => "A natural person who is ...",
                        "Ispapi-Name" => "X-US-NEXUS-CATEGORY",
                        "Required" => true
                    ], [
                        "Name" => "Nexus Country",
                        "LangVar" => "hxflagsnexuscountry",
                        "Description" => "<div>Specify the two-letter country-code of the registrant (if Nexus Category is either C31 or C32).</div>",
                        "Ispapi-Name" => "X-US-NEXUS-VALIDATOR",
                        "Required" => [
                            "Nexus Category" => [
                                "C31",
                                "C32"
                            ]
                        ],
                        "Ispapi-Format" => 'UPPERCASE'
                    ]
                ],
                ".vote" => self::disableWHMCSFields(["Agreement"]),
                ".voto" => self::disableWHMCSFields(["Agreement"]),
                ".xxx" => [
                    self::getRegulatedTLDField(".xxx"),
                    [
                        "Name" => "Resolving Domain",
                        "Description" => "(Should this .XXX domain resolve?)",
                        "Type" => "dropdown",
                        "Ispapi-Name" => "X-XXX-NON-RESOLVING",
                        "Options" => implode(",", [
                            "0|Yes - Domain should resolve",
                            "1|No  - Domain should not resolve"
                        ]),
                        "Default" => "0|Yes - Domain should resolve",
                        "Required" => false
                    ], [
                        "Name" => "Membership ID",
                        "Type" => "text",
                        "Description" => "(Required in order to make your .XXX domain resolving)",
                        "Ispapi-Name" => "X-XXX-MEMBERSHIP-CONTACT",
                        "Default" => "",
                        "Required" => false
                    ]
                ],
                ".za" => [ self::getHighlyRegulatedTLDField(".za") ],
            ],
            "transfer" => [
                ".ca" => [
                    self::getLegalTypeField([
                        "Options" => implode(",", [
                            "CCO|Corporation",
                            "CCT|Canadian Citizen",
                            "RES|Permanent Resident of Canada",
                            "GOV|Government or government entity in Canada",
                            "EDU|Canadian Educational Institution",
                            "ASS|Canadian Unincorporated Association",
                            "HOS|Canadian Hospital",
                            "PRT|Partnership Registered in Canada",
                            "TDM|Trade-mark registered in Canada (by a non-Canadian owner)",
                            "TRD|Canadian Trade Union",
                            "PLT|Canadian Political Party",
                            "LAM|Canadian Library Archive or Museum",
                            "TRS|Trust established in Canada",
                            "ABO|Aboriginal Peoples (individuals or groups) indigenous to Canada",
                            "INB|Indian Band recognized by the Indian Act of Canada",
                            "LGR|Legal Representative of a Canadian Citizen or Permanent Resident",
                            "OMK|Official mark registered in Canada",
                            "MAJ|Her Majesty the Queen"
                        ]),
                        "Ispapi-Name" => "X-CA-LEGALTYPE"
                    ])
                ],
                ".it" => [
                    [
                        "Name" => "Accept Section 6",
                        "Description" => (
                            "<b>Section 6 - Consent to the processing of personal data for diffusion and accessibility via the Internet</b><br/>" .
                            "<div style='text-align:justify;margin-bottom:10px;'>The interested party, after reading the above disclosure, gives " .
                            "consent to the dissemination and accessibility via the Internet, as defined in the disclosure above. Giving consent " .
                            "is optional, but absence of consent does not allow the dissemination and accessibility of Internet data.</div>"
                        ),
                        "Type" => "tickbox",
                        "Ispapi-Name" => "X-IT-ACCEPT-DIFFUSION-AND-ACCESSIBILITY-TAC",
                        "Required" => false
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

        //tlds supporting trustee: .bayern, .berlin, .de, .eu, .forex, .it, .jp, .ruhr, .sg, AFNIC TLDs
        ## LOCAL PRESENCE / TRUSTEE SERVICE ##
        ## NOTE: if you want to offer local presence service, add the trustee service price to the domain registration AND transfer price ##
        ## for reference: https://requests.whmcs.com/topic/integrate-trustee-service-as-generic-domain-add-on
        ## Missing: Prodiving a TAC document
        /*
        $additionaldomainfields[self::entity][$tld] = [];
        $additionaldomainfields[self::entity][$tld][] = [
            "Name" => "Local Presence Service",
            "LangVar" => "hxflagstactrustee",
            "Type" => "dropdown",
            "Options" => ",1|Use Local Presence Service (for Registrant and Admin-Contact)",
            "Ispapi-Name" => "X-BERLIN-ACCEPT-TRUSTEE-TAC",
            "Description" => "(Required in case you are <b>not</b> domiciled in Berlin)",
            "Default" => ""
        ];
        */

        // add translation support in case no generic LangVar field got configured
        foreach (self::$additionalfieldscfg[self::$entity] as $type => &$tlds) {
            foreach ($tlds as $tldkey => &$fields) {
                foreach ($fields as &$f) {
                    if (!isset($f["LangVar"])) {// follow a prefixed but similar way WHMCS uses for LangVar ids
                        $f["LangVar"] = "hxflags" . strtolower(str_replace(".", "", $tldkey) .  "tld" . preg_replace("/[^a-z0-9]/i", "", $f['Name']));
                    }
                }
            }
        }
    }

    public static function getAFNICFields()
    {
        return self::disableWHMCSFields(
            ["Legal Type","Info","Birthplace City","Birthplace Country","Birthplace Postcode","SIRET Number","VAT Number"],
            [
                self::getLegalTypeField([
                    "Options" => implode(",", [
                        "INDIV|Individual",
                        "ORG1|Company with VATID or SIREN/SIRET number",
                        "ORG2|Company with European Trademark",
                        "ORG3|Company with DUNS Number",
                        "ORG4|Company local identifier",
                        "ASS|French Association"
                    ]),
                ]),[
                    "Name" => "VATID or SIREN/SIRET number",
                    "LangVar" => "hxflagsafnicvatid",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-LEGAL-ID",
                    "Required" => [
                        "Legal Type" => [
                            "ORG1"
                        ]
                    ],
                    "Description" => "(Only for companies with VATID or SIREN/SIRET number)",
                ],[
                    "Name" => "Trademark Number",
                    "LangVar" => "hxflagsafnictrademark",
                    "Ispapi-Name" => "X-FR-REGISTRANT-TRADEMARK-NUMBER",
                    "Required" => [
                        "Legal Type" => [
                            "ORG2"
                        ]
                    ],
                    "Description" => "(Only for companies with a European trademark)"
                ],[
                    "Name" => "DUNS Number",
                    "LangVar" => "hxflagsafnicduns",
                    "Ispapi-Name" => "X-FR-REGISTRANT-DUNS-NUMBER",
                    "Required" => [
                        "Legal Type" => [
                            "ORG3"
                        ]
                    ],
                    "Description" => "(Only for companies with DUNS number)",
                ],[
                    "Name" => "Local ID",
                    "LangVar" => "hxflagsafniclocalid",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-LOCAL-ID",
                    "Required" => [
                        "Legal Type" => [
                            "ORG4"
                        ]
                    ],
                    "Description" => "(Only for companies with local identifier)",
                ],[
                    "Name" => "Date of Declaration [JO]",
                    "LangVar" => "hxflagsafnicjodod",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-JO-DATE-DECLARATION",
                    "Required" => [
                        "Legal Type" => [
                            "ASS"
                        ]
                    ],
                    "Description" => "(Only for french association) <div>The date of declaration of the association in the form <b>YYYY-MM-DD</b></div>",
                ],[
                    "Name" => "Number [JO]",
                    "LangVar" => "hxflagsafnicjonumber",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-JO-NUMBER",
                    "Required" => [
                        "Legal Type" => [
                            "ASS"
                        ]
                    ],
                    "Description" => "(Only for french association) <div>The number of the Journal Officiel</div>",
                ],[
                    "Name" => "Page of Announcement [JO]",
                    "LangVar" => "hxflagsafnicjopage",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-JO-PAGE",
                    "Required" => [
                        "Legal Type" => [
                            "ASS"
                        ]
                    ],
                    "Description" => "(Only for french association) <div>The page of the announcement in the Journal Officiel</div>",
                ],[
                    "Name" => "Date of Publication [JO]",
                    "LangVar" => "hxflagsafnicjodop",
                    "Type" => "text",
                    "Ispapi-Name" => "X-FR-REGISTRANT-JO-DATE-PUBLICATION",
                    "Required" => [
                        "Legal Type" => [
                            "ASS"
                        ]
                    ],
                    "Description" => "(Only for french association) <div>The date of publication in the Journal Officiel in the form <b>YYYY-MM-DD</b></div>",
                ]
            ]
        );
    }

    public static function getLegalTypeField($overrides = [])
    {
        $f = array_merge([
            "Name" => "Legal Type",
            "LangVar" => "hxflagslegaltype",
            "Options" => ""
        ], $overrides);
        $f["Default"] = explode(",", $f["Options"])[0];
        return $f;
    }

    public static function getFaxFormField($query)
    {
        return [
            "Name" => "Fax required",
            "LangVar" => "hxflagsfax",
            "Type" => "tickbox",
            "Description" => (
                "I confirm I will send <a href='https://www" .
                (self::$isOTE ? "-ote" : "" ) . ".domainform.net/form/" . $query .
                "'>this form back</a> to complete the registration process."
            ),
            "Default" => "",
            "Required" => true
        ];
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

    public static function getContactIdentificationField($contacttype, $overrides = [])
    {
        return array_merge([
            "Name" => ucfirst($contacttype) . " ID number",
            "LangVar" => "hxflags" . strtolower($contacttype) . "idnumber",
            "Type" => "text",
            "Required" => false,
            "Ispapi-Name" => "X-" . $contacttype . "-IDNUMBER"
        ], $overrides);
    }

    public static function getContactTypeField($contacttype, $overrides = [])
    {
        $f = array_merge([
            "Name" => ucfirst($contacttype) . " ID Type",
            "LangVar" => "hxflags" . strtolower($contacttype) . "idtype",
            "Type" => "dropdown",
            "Options" => "",
            "Required" => false,
            "Ispapi-Name" => "X-" . $contacttype . "-IDTYPE"
        ], $overrides);
        $f["Default"] = explode(",", $f["Options"])[0];
        return $f;
    }

    public static function getVATIDField($tld, $contacttype, $overrides = [])
    {
        return array_merge([
            "Name" => ucfirst($contacttype) . " VAT ID",
            "LangVar" => "hxflags" . strtolower($contacttype) . "vatid",
            "Type" => "text",
            "Description" => "",
            'Required' => [
                'Legal Type' => [
                    'Organization'
                ]
            ],
            "Ispapi-Name" => "X-" . $contacttype . "-VATID"
        ], $overrides);
    }

    public static function getIntendedUseField()
    {
        return [
            "Name" => "Intended Use",
            "LangVar" => "hxflagsintendeduse",
            "Type" => "text",
            "Required" => true,
            "Ispapi-Name" => "X-CORE-INTENDED-USE"
        ];
    }

    public static function getAllocationTokenField($tld)
    {
        $map = [
            ".bank" => "https://www.register.bank/get-started/",
            ".insurance" => "https://www.register.insurance/get-started/"
        ];
        $url = isset($map[$tld]) ? $map[$tld] : "#";

        return [
            "Name" => "Registry's Allocation Token",
            "LangVar" => "hxflagsallocationtoken",
            "Type" => "text",
            "Required" => true,
            "Description" => (
                "To register a " . strtoupper($tld) . " domain, you must provide the allocation token issued by the registry. " .
                "Please complete the registrant application <a href='" . $url . "' target='_blank'>here</a> to obtain the token."
            ),
            "Ispapi-Name" => "X-ALLOCATIONTOKEN",
            "Default" => ""
        ];
    }

    public static function disableWHMCSFields($names, $fields = [])
    {
        foreach ($names as $name) {
            $fields[] = [
                "Name" => $name,
                "Remove" => true
            ];
        }
        return $fields;
    }

    public static function getTLDClass($tld)
    {
        return strtoupper(preg_replace("/\./", "", $tld, 1));
    }

    public static function getTAC($tld)
    {
        $tac = [
            ".abogado" => "http://nic.law/eligibilitycriteria/",
            ".ae" => "https://www.nic.ae/content.jsp?action=termcond_ae",
            ".boats" => "https://get.boats/policies/",
            ".broker" => "https://nic.broker/",
            ".cat" => "http://domini.cat/en/domini/rules-cat-domain",
            ".cfd" => "https://nic.cfd/",
            ".eco" => "https://home.eco/registrars/policies/",
            ".es" => "https://www.dominios.es/dominios/en/todo-lo-que-necesitas-saber/sobre-registros-de-dominios/terms-and-conditions",
            ".fi" => "https://domain.fi/info/en/index/hakeminen/kukavoihakea.html",
            ".forex" => "https://nic.forex/",
            ".health" => "https://get.health/registration-policies",
            ".hk" => "https://www.hkirc.hk/content.jsp?id=3#!/6",
            ".homes" => "https://domains.homes/Policies/",
            ".id" => "https://pandi.id/regulasi/",
            ".law" => "http://nic.law/eligibilitycriteria/",
            ".markets" => "https://nic.markets/",
            ".ngo" => "https://thenew.org/org-people/about-pir/policies/ngo-ong-policies/",
            ".nu" => "https://internetstiftelsen.se/app/uploads/2019/02/terms-and-conditions-nu.pdf",
            ".paris" => "http://bienvenue.paris/registry-policies-paris/",
            ".ru" => "http://www.cctld.ru/en/docs/rules.php",
            ".se" => "https://internetstiftelsen.se/app/uploads/2019/02/registreringsvillkor-se-eng.pdf",
            ".spreadbetting" => "https://nic.spreadbetting/",
            ".trading" => "https://nic.trading/",
            ".xxx" => "http://www.icmregistry.com/about/policies/registry-registrant-agreement/",
            ".za" => "https://www.zadna.org.za/"
        ];
        return isset($tac[$tld]) ? $tac[$tld] : false;
    }

    public static function getTACDescription($tld, $type = "REGULATED")
    {
        $tac = self::getTAC($tld);
        if ($type == "HIGHLYREGULATED") {
            if ($tac) {
                $descr = (
                    "Tick to confirm that you certify that the Registrant is eligibile to register this domain and that all provided information is " .
                    "true and accurate. Eligibility criteria may be viewed <a href='". $tac . "' target='_blank'>here</a>."
                );
                if ($tld != ".eco") {
                    return $descr;
                }
                return (
                    $descr . "<br/>All .ECO domain names " .
                    "will be first registered with \"server hold\" status pending the completion of the minimum requirements of the Eco Profile, namely, " .
                    "the .ECO registrant 1) affirming their compliance with the .ECO Eligibility Policy and 2) pledging to support positive change for " .
                    "the planet and to be honest when sharing information on their environmental actions. The registrant will be emailed with instructions " .
                    "on how to create an Eco Profile. Once these steps have been completed, the .ECO domain will be immediately activated by the registry."
                );
            }
            return (
                "Tick to confirm the <b>Safeguards for Highly-regulated TLDs</b>:<br/>" .
                "<div style='text-align:justify'>You understand and agree that you will abide by and be compliant with these additional terms:" .
                "<ol><li>Administrative Contact Information. You agree to provide administrative contact information, which must be kept up-to-date, " .
                "for the notification of complaints or reports of registration abuse, as well as the contact details of the relevant regulatory, or " .
                "industry selfregulatory, bodies in their main place of business.</li>" .
                "<li>Representation. You confirm and represent that you possesses any necessary authorizations, charters, licenses and/or other related " .
                "credentials for participation in the sector associated with such Highly-Regulated TLD.</li>" .
                "<li>Report of Changes of Authorization, Charters, Licenses, Credentials. You agree to report any material changes to the validity of " .
                "your authorizations, charters, licenses and/or other related credentials for participation in the sector associated with the Highly-Regulated " .
                "TLD to ensure you continue to conform to the appropriate regulations and licensing requirements and generally conduct you activities in the " .
                "interests of the consumers you serve..</li></ol></div>"
            );
        }
        if ($type == "INDIVIDUALREGULATED") {
            return ("Tick to confirm the <a href='" . $tac . "' target='_blank'>Terms for Individuals</a>");
        }
        if (!$tac) {
            $tac = '#';
        }
        $descr = (
            "Tick to confirm that you agree to the <a href='" . $tac . "' target='_blank'>Registry Terms and Conditions of Registration</a> upon new registration of " .
            $tld . " domain names."
        );
        if ($tld != ".ngo") {
            return $descr;
        }
        return (
            $descr . "<div style='padding:10px 0px;'>The registration of a .NGO domain name is bundled with an .ONG domain name without additional costs. " .
            "Changes on the .NGO Domain will be auto-applied to the .ONG Domain.</div>"
        );
    }

    public static function getRegulatedTLDField($tld)
    {
        return [
            "Name" => "Agreement",
            "Type" => "tickbox",
            "Description" => self::getTACDescription($tld, "REGULATED"),
            "Required" => true,
            "LangVar" => "hxflagstacagreement",
            "Ispapi-Name" => "X-" . self::getTLDClass($tld) . "-ACCEPT-REGISTRATION-TAC"
        ];
    }

    public static function getIndividualRegulatedTLDField($tld, $overrides = [])
    {
        return [
            "Name" => "Terms for Individuals",
            "LangVar" => "hxflagstacagreementindiv",
            "Type" => "tickbox",
            "Description" => self::getTACDescription($tld, "INDIVIDUALREGULATED"),
            "Required" => true,
            "LangVar" => "hxflagstacagreement",
            "Ispapi-Name" => "X-" . self::getTLDClass($tld) . "-ACCEPT-INDIVIDUAL-REGISTRATION-TAC"
        ];
    }

    public static function getHighlyRegulatedTLDField($tld)
    {
        return [
            "Name" => "Highly Regulated TLD",
            "LangVar" => "hxflagstachighlyregulated",
            "Type" => "tickbox",
            "Required" => true,
            "Description" => self::getTACDescription($tld, "HIGHLYREGULATED"),
            "Ispapi-Name" => "X-" . self::getTLDClass($tld) . "-ACCEPT-HIGHLY-REGULATED-TAC"
        ];
    }
    
    /**
     * @param array $command API command to add additional domain field parameters to
     * @param string $registrantcountry country of the registrant
     */
    public function addToCommand(&$command, $registrantcountry)
    {
        foreach ($this->getFields() as $fieldKey => $values) {
            $iname = $this->getConfigValue($fieldKey, "Ispapi-Name");
            if (empty($iname)) {
                continue;
            }
            $ignoreCountries = $this->getConfigValue($fieldKey, "Ispapi-IgnoreForCountries");
            if (!(
                empty($ignoreCountries) ||
                (!empty($registrantcountry) && !in_array(strtoupper($registrantcountry), $ignoreCountries))
            )) {
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

    public static function getAdditionalDomainFields($tld, $type = "register")
    {
        $transientKey = "ispapiFields" . self::$entity . ucfirst($type) . ucfirst($tld);
        $fields = \WHMCS\TransientData::getInstance()->retrieve($transientKey);
        if ($fields) {
            $fields = json_decode($fields, true);
            if (isset($fields) && is_array($fields)) {
                return ["fields" => $fields];
            }
        }
        // check if a configuration exists for the given order type (register/transfer)
        $cfg = self::$additionalfieldscfg[self::$entity];
        if (is_null($cfg) || !Object.prototype.call('hasOwnProperty', $cfg, $type)) {
            return [];
        }
        
        // check if a configuration exists for the given tld
        if (Object.prototype.call('hasOwnProperty', $cfg[$type], $tld)) {
            \WHMCS\TransientData::getInstance()->store($transientKey, json_encode($cfg[$type][$tld]), 86400 * 30);
            return $cfg[$type][$tld];
        }

        // check if a configuration exists for 2nd level fallback (in case of incoming 3rd level tld)
        $tldfb = preg_replace("/^[^.]+/", "", $tld);
        if ($tld != $tldfb && Object.prototype.call('hasOwnProperty', $cfg[$type], $tldfb)) {
            \WHMCS\TransientData::getInstance()->store($transientKey, json_encode($cfg[$type][$tldfb]), 86400 * 30);
            return $cfg[$type][$tldfb];
        }

        //nothing found ...
        return [];
    }
}
