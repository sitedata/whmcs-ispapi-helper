# Additional domain fields in WHMCS

The default list of **default WHMCS' additional domain fields** can be found under `/resources/domain/dist.additionalfields.php`. Do **NOT** modify this file as it will be probably overwritten by WHMCS Upgrades.

Custom additional fields configurations could be applied in the past by keeping them in file `/resources/domain/additionalfields.php` (prior to WHMCS 7.0 `/includes/additionaldomainfields.php`), we refer to that file by OVERRIDEFILE below. The new and better way is to use method `_AdditionalDomainFields` of the registrar module which we introduced with v3.0.0 of our module. Read below.

Target of this documentation is to basically combine the standard [WHMCS Documentation](https://docs.whmcs.com/Additional_Domain_Fields) with the extensions made for the HEXONET/ISPAPI provider module.

## Known Bugs / Support Tickets / Feature Requests

* Wrong Dropdown List Entry pre-selected, should be the first one with empty value. [#PHW-648709](https://www.whmcs.com/members/viewticket.php?tid=PHW-648709&c=VtIFzrAa)
* Dropdown List Entry with `falsy` value is returned as `missing` in submission when field is configured as required field. [#CORE-14277](https://www.whmcs.com/members/viewticket.php?tid=WRJ-298239&c=PtkKH0Ck)
* Feature Request [`Ability to separate registration additional fields from transfer additional fields`](https://requests.whmcs.com/topic/ability-to-separate-registration-additional-fields-from-transfer-additional-field)
* Rejected Feature Request [`Finalize translation support for additional domain fields`](https://requests.whmcs.com/topic/finalize-translation-support-for-additional-domain-fields)

Feel free to upvote the feature requests - very appreciated!

## Features of our implementation

* Standardized way
* Shipped directly with the registrar module
* No manual effort with additional domain fields
* Auto-Cleanup of WHMCS built-in additional domain fields
* Still, our solution is overridable
* Auto-Generate Translation keys out of configuration if not provided
* 100% translation support (covering Name, Options, Description): english, german, french
* Changes can be rolled out in short through a new release
* Localized County Dropdown Lists can be realized in ease
* Localized Language Dropdown Lists can be realized in ease
* Auto-add empty valued option in case dropdown list is configured to be optional
* Auto-add Fax Form fields (linking to our [form generator](https://domainform.net))
* Configure Options in array notation for better readability
* Support of Conditional Requirements, but compatible with WHMCS < 7.9 (we require >=7.8!)
* Auto-prefill with previous configuration values in owner change process

## Activate OUR default additional fields configuration

Nothing you need to care about. Method `_AdditionalDomainFields`, available in our registrar module since v3.0.0, cares about everything you need.
If you have already the OVERRIDEFILE file in use, please remove the entire file if you're just using `Ispapi` as registrar.
If you have there also TLD configurations for other registrars, be so kind to just cleanup TLD configurations affecting TLDs offered over us.

**NOTE:** We have **no** Local Presence Service activated by default. Please have a further read [here](#local-presence-trustee-service).

Ensure to follow [this section](#translating-additional-domain-fields) to get translations shown, otherwise texts are missing.

## Uniqueness of domain fields

The property `Name` in the additional domain fields is used as unique field identifier per TLD. Thus specifying it is **MANDATORY**, when you introduce your custom fields or when overriding default field configurations shipped with WHMCS itself.

NOTE: **NEVER** apply translations by translating the `Name` field directly. WHMCS / our registrar module provides mechanisms for that. Please refer to the [translation guide](#translating-additional-domain-fields) instead.

## Customizing additional domain fields

If an addtional domain field is required for domain registration / transfer / trade / update for a TLD offered over us / registrar `Ispapi` and is not provided, then open a github issue and let us know. If you want to have something changed (translations, missing field, field review), also open a github issue. We will care about such cases in short!

Any other customizing of additional domain fields on customer's side should happen in the OVERRIDEFILE which again can be used to overwrite our defaults. You can follow the steps described in the standard [WHMCS Documentation](https://docs.whmcs.com/Additional_Domain_Fields) to get this realized. Still, we would prefer to know about such cases to be able to improve.

## Translating additional domain fields

Based on the standard [WHMCS Documentation](https://docs.whmcs.com/Additional_Domain_Fields) section `Translating a Field Name`, follow the below steps to get it realized the best.
We suggest the below way instead of copy'n'paste of the contents, because in that way, you don't have to care about updating our translations any longer.

* Find language files in folder `install/registrars/ispapi/lang/overrides` of your downloaded archive
* Create your desired language file in folder `/lang/overrides` of your WHMCS Installation if not existing yet - as described [here](https://developers.whmcs.com/languages/overrides/)
* Ensure the file content starts with `<?php`.
* Add line e.g. `include implode(DIRECTORY_SEPARATOR, [ROOTDIR, "modules", "registrars", "ispapi", "lang", "overrides", "english.php" ]);` to the english file.

Do this for every language you offer, but change the file name in the above code snippet accordingly.

**NOTE:** Even though translating the `Description` or `Options` field is not possible by WHMCS built-in configuration parameters for additional fields ([see](https://requests.whmcs.com/topic/finalize-translation-support-for-additional-domain-fields)), we have found a generic solution for exactly that! Please note that you should **not** modify our translation files. Let us know if you need something changed, we can support. Just open a github issue - we will care about it in short! In case you're missing a language file, feel free to work on a Pull Request to get it added. Your Support is very appreciated!

Done!

We have translation files available for `arabic`, `english`, `germnan` and `french`. For all other languages we just fallback to english. Feel free to work on translations and forward changes / new languages to our hands by pull request or github issue. Also partial translations are welcome!

### Nomenclature of translation IDs

In general our translation ids start with `hxflags` prefix to allow identifying them in ease.

* tld-specific ids: Start with the translation prefix followed by undotted tld string plus keyword `tld` e.g. `hxflagsustld`
* tld-specific field `Name` translation id, to be used in `LangVar`: Start with tld-specific id plus the field name (`Name` value e.g. `Legal Type`), but stripped to chars and numbers e.g. `hxflagsustldlegaltype`
* tld-specific field `Description` translation id: Start with tld-specific field name translation id plus keyword `descr` e.g. `hxflagsustldlegaltypedescr`
* tld-specific field `Options` translation ids: Start with tld-specific field name translation id plus option value in lower case e.g. `hxflagsustldlegaltypecco`. See .CA configuration.
* generic translations: start with translation prefix plus generic identifier e.g. `hxflagsallocationtoken` and follow then the same principles as the tld-specific ones.
* terms and conditions field: Start with translation prefix followed by keyword `tac`, followed by type of terms and conditions e.g. `hxflagstacregulated`, `hxflagstachighlyreguled`, `hxflagsindividualregulated` and follow then the same principles as the tld-specific ones.
* tld-specific terms and conditions field: Starts with the normal id of terms and conditions fields followed by the undotted tld format e.g `hxflagstachighlyregulateddescreco`

This is just to let you know how we internally keep this things to provide you easier overview how translations are managed in the translation files.

## Developer Resources

Our tld-specific additional field configurations are part of the [whmcs-ispapi-helper](https://github.com/hexonet/whmcs-ispapi-helper) repository in library [AdditionalFields.class.php](https://github.com/hexonet/whmcs-ispapi-helper/blob/master/AdditionalFields.class.php). This repository is reused as git submodule in folder `registrars/ispapi/lib`.

**NOTE:**

* Customers should **NEVER** apply changes to this file as you'll lose them when upgrading to a newer registrar module version. Let us know if you need something!
* WHMCS up to now, doesn't reflect the correct type in `$params["type"]` in method `_AddtionalDomainFields`, so that it is impossible to distinguish between transfers and registrations up to now. When this is fixed, everything will correctly work on our side out of the box.

### Introducing a new tld-specific configuration

Add a new tld-specific configuration to the static class variable `self::$additionalfieldscfg` in class `AdditionalFields.class.php`. Of course the logic already starts here...

* Case 1: 2nd level extension requires additional fields, 3rd level extension not
  -> Just create empty entries for the 3rd level extensions. This might of course be a corner-case, so just documenting it here. e.g. `".my.tld" => [],`
* Case 2: 2nd level extension AND 3rd level extensions share the additional fields configuration
  -> Just create a configuration entry for the 2nd level extension. It will be reused for the 3rd level extensions automatically.
* Case 3: 2nd level extension does not require additional fields, 3rd level extensions do.
  -> Just create configuration entries for the 3rd level extensions.

There are several static functions you can use to achieve what you need. Continue reading, a short overview follows at the end of this section, too.

In general you could simply provide a configuration by not using such a function, so by just completely specifying the configuration as covered in the WHMCS standard documentation.

**NOTE:**

The main differences to the WHMCS standard documentation are that we configure `Description` with a translation key, not with plain text or html. The plain text has then to be provided through the previously mentioned translation files using that translation key. If the `Description` field contains any of the placeholders (`{TAC}`, `{TLD}`) the translation logic cares about replacing that part later on dynamically.
In case of placeholder `{TAC}` ensure the terms and conditions document is configured in static method `getTAC` in `AdditionalFields.class.php` for your TLD of choice.

Further more OUR configuration considers `Options` as Array and not in coma separated string notation. Use static method `buildOptions` of `AdditionalFields.class.php` if you don't use existing static methods to get a standardized field configuration generated. e.g. `self::buildOptions(".us", "Nexus Category", ["C31", "C32"])`. Ensure to only provide your values in 3rd parameter. This method then auto-generates translation keys for each option and returns something like `["C31|hxflagsustldnexuscategoryc31","C32|hxflagsustldnexuscategoryc32"]`. Also use these translation keys in the previously mentioned translation files to get the plain text or html contents introduced.

How does the translation logic work in detail? Well, we call the static method `translate` of `AdditionalFields.class.php` for a configuration detected, before we return the configuration. This allows us to keep configurations language independent in memory cache.

Of course, we have to review in case WHMCS finalizes the additional domain fields translation logic, but we will know about that step because of our open feature request.

#### method `getAllocationTokenField`

Can be used to generate an `Allocation Token` input field. For TLDs that require a Token for registration provided by the registry / provider.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"

##### Return Value

* domain field configuration array

##### Example

```php
".bank" => [self::getAllocationTokenField(".bank")],
```

#### method `getContactIdentificationField`

Can be used to generate a contact-specific `ID Number` input field (e.g. `Admin ID Number`).
Internally used by method `getRegistrantIdentificationField`.

##### Input Parameters

* $contacttype: contact type e.g. `REGISTRANT`, `ADMIN`, `TECH` or `BILLING`.
* $overrides: associative array to allow overriding defaults of the field generator. Optional.

##### Return Value

* domain field configuration array

##### Example

```php
".mytld" => [
    self::getContactIdentificationField("REGISTRANT", [
        "Required" => true,
        "Ispapi-Name" => "X-CN-REGISTRANT-ID-NUMBER"
    ])
],
```

#### method `getContactTypeField`

Can be used to generate a contact-specific type dropdown list field (e.g. `Admin Type`).

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"
* $contacttype: contact type e.g. `REGISTRANT`, `ADMIN`, `TECH` or `BILLING`. Makes `Ispapi-Name` contact-specific.
* $overrides: associative array to allow overriding defaults of the field generator. Optional.

##### Return Value

* domain field configuration array

##### Example

```php
".cn" => [
    self::getContactTypeField(".cn", "REGISTRANT", [
        "Options" => [ "SFZ", "HZ", /* ... */],
        "Ispapi-Name" => "X-CN-REGISTRANT-ID-TYPE"// override the default `X-REGISTRANT-IDTYPE`
    ])
]
```

#### method `getCountryField`

Can be used to generate a countries dropdown list field. It uses WHMCS' internals to map country codes to country names.
**NOTE:** If you want to customize countries, follow [this guide](https://docs.whmcs.com/Customising_Countries_and_Calling_Codes).

##### Input Parameters

* $overrides: associative array to allow overriding defaults of the field generator. Mandatory.

##### Return Value

* domain field configuration array

##### Example

```php
".eu" => [
    self::getCountryField([
        "Name" => "Registrant Citizenship",
        "Options" => ["AT", "BE", /* ... */],
        "Description" => "euregistrantcitizenshipdescr",
        "Ispapi-Name" => "X-EU-REGISTRANT-CITIZENSHIP"
    ])
],
```

or to get all possible countries:

```php
".hk" => [
    self::getCountryField([
        "Name" => "Registrant Document Origin Country",
        "Required" => true,
        "Options" => "{CountryCodeMap}", // <---- will be replaced accordingly
        "Ispapi-Name" => "X-HK-REGISTRANT-DOCUMENT-ORIGIN-COUNTRY"
    ])
],
```

NOTE: Configuration field `Required` will lead to get an empty value first entry being auto-added if not `true`.

#### method `getHighlyRegulatedTLDField`

Can be used to generate an Agreement field to accept Terms and Conditions of highly regulated domain extensions. Ensure to configure the TaC document in method `getTAC`. If you need to customize the default `Description`, check method `getTACDescription` to specify a tld-specific translation identifier which can then be used within the translation files.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"

##### Return Value

* domain field configuration array

##### Example

```php
".abogado" => [
    self::getHighlyRegulatedTLDField(".abogado")
],
```

#### method `getIndividualRegulatedTLDField`

Can be used to generate an Agreement field to accept Terms and Conditions for Individuals of regulated domain extensions. Ensure to configure the TaC document in method `getTAC`. If you need to customize the default `Description`, check method `getTACDescription` to specify a tld-specific translation identifier which can then be used within the translation files.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"
* $overrides: associative array to allow overriding defaults of the field generator. Optional.

##### Return Value

* domain field configuration array

##### Example

```php
".es" => [
    self::getIndividualRegulatedTLDField(".es")
],
```

plus overriding defaults

```php
".hk" => [
    // ...
    self::getIndividualRegulatedTLDField(".hk", [
        "Name" => "Check my custom Agreement"
    ])
]
```

#### method `getIntendedUseField`

Can be used to generate a `Intended Use` input list field.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker". Optional.
* $overrides: associative array to allow overriding defaults of the field generator. Optional.

##### Return Value

* domain field configuration array

##### Example

```php
".madrid" => [ self::getIntendedUseField() ],
```

plus overriding defaults to also have dropdown list instead

```php
".us" => [
    self::getIntendedUseField(".us", [
        "Ispapi-Name" => "X-US-NEXUS-APPPURPOSE",
        "Type" => "dropdown",
        "Options" => [ "P1", "P2", "P3", "P4", "P5" ]
    ])
],
```

#### method `getLanguageField`

Can be used to generate a language dropdown list field while having the Options translated to localized translations. Comparable to the language dropdown list on WHMCS' client area entry page.

##### Input Parameters

* $overrides: associative array to allow overriding defaults of the field generator. Optional.

##### Return Value

* domain field configuration array

##### Example

```php
".ca" => [
    self::getLanguageField([
        "Name" => "Contact Language",
        "Options" => ["EN", "FR"],
        "Required" => true,
        "Ispapi-Name" => "X-CA-LANGUAGE"
    ])
],
```

#### method `getLegalTypeField`

Can be used to generate a generic `Legal Type` dropdown list field. Can be customized by input parameter `$overrides` to be reused for other type list fields by overriding `Name` and `Options`.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"
* $overrides: associative array to allow overriding defaults of the field generator. Optional.

##### Return Value

* domain field configuration array

##### Example

```php
".ca" => [
    self::getLegalTypeField(".ca", [
        "Options" => ["CCO", "CCT", /* ... */ ],
        "Ispapi-Name" => "X-CA-LEGALTYPE"
    ])
],
```

#### method `getNexusCategoryField`

Can be used to generate a `Nexus Category` field, a dropdown list with values for selection.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"
* $overrides: associative array to allow overriding defaults of the field generator. Optional.

##### Return Value

* domain field configuration array

##### Example

```php
".sydney" => [
    self::getNexusCategoryField(".sydney", [
        "Options" => [ "A", "B", "C" ]
    ])
],
```

more customizing

```php
".melbourne" => [
    self::getNexusCategoryField(".melbourne", [
        "Options" => [ "A", "B", "C" ],
        "Description" => "melbournenexuscategorydescr"
    ])
],
```

#### method `getOptions`

Can be used to generate the `Options` configuration field in an additional field configuration.

**NOTE:**

Not to be used together with the static methods as the static methods call this method internally. As you can see in the Examples, specify `Options` please as simple array in case you use a static method. If you manually configure an additional domain field, use `getOptions` when specifying `Options`. Never mixup both things!

This method also auto-adds an empty value option in case `$isRequired` Parameter is not `true`.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"
* $transprefix: translation prefix, suggestion: use the `Name` field value for this
* $optvals: array of option values. Do not provide anything else than the values, we care about the option labels / translations.
* $isRequred: forward your configuration setting of `Required` field here. Auto-adds an empty value option in case `$isRequired` Parameter is not `true`.

##### Return Value

* generated options array including translation ids per option

##### Example

```php
".hk" => [
    [
        "Name" => "Registrant Document Type",
        "Type" => "dropdown",
        "Options" => self::getOptions(".hk", "Registrant Document Type", [
            "HKID", "OTHID", /* ... */
        ]),
        // ...
        "Ispapi-Name" => "X-HK-REGISTRANT-DOCUMENT-TYPE"
    ]
],
```

#### method `getRegistrantIdentificationField`

Can be used to generate a `Registrant ID Number` input field.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"
* $overrides: associative array to allow overriding defaults of the field generator. Optional.

##### Return Value

* domain field configuration array

##### Example

```php
".ie" => [
    self::getRegistrantIdentificationField(".ie", [
        "Required" => false
    ])
],
```

#### method `getRegulatedTLDField`

Can be used to generate an Agreement field to accept Terms and Conditions for normal regulated domain extensions. Ensure to configure the TaC document in method `getTAC`. If you need to customize the default `Description`, check method `getTACDescription` to specify a tld-specific translation identifier which can then be used within the translation files.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"
* $overrides: associative array to allow overriding defaults of the field generator. Optional.
* $descrid: if a TLD needs more than one agreement field, you can use this way to specify a specific translation identifier suffix for field `Description`. Optional. (e.g. `.it`)

##### Return Value

* domain field configuration array

##### Example

```php
".ngo" => [ self::getRegulatedTLDField(".ngo") ],
```

using multiple agreement files and $descrid

```php
".it" => [
    self::getRegulatedTLDField(".it", "section3", [
        "Name" => "Accept Section 3",
        "Ispapi-Name" => "X-IT-ACCEPT-LIABILITY-TAC"
    ]),
    self::getRegulatedTLDField(".it", "section5", [
        "Name" => "Accept Section 5",
        "Ispapi-Name" => "X-IT-ACCEPT-REGISTRATION-TAC",
        "Required" => false
    ]),
    // ...
],
```

#### method `getVATIDField`

Can be used to generate a contact type specific VATID input field.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"
* $contacttype: contact type e.g. `REGISTRANT`, `ADMIN`, `TECH` or `BILLING`.
* $overrides: associative array to allow overriding defaults of the field generator. Optional.

##### Return Value

* domain field configuration array

##### Example

```php
".nu" => [
    self::getVATIDField(".nu", "REGISTRANT", [
        'Required' => false,
        "Ispapi-Name" => "X-VATID"
    ])
],
```

#### method `getYesNoField`

Can be used to generate an additional domain field to agree by choosing `Yes` or to not agree by choosing `No`. Can be used for any such purpose as it is very customizable.
Default Option values are `YES` and `NO`. We support also `["Y", "N"]` and `["0","1"]` with default translations mapped to `Yes` and `No`.

##### Input Parameters

* $tld: dotted domain extension format e.g. ".broker"
* $overrides: associative array to allow overriding defaults of the field generator. Optional.
* $customlabels: if you need custom translations for your Options so, something more than just `Yes` and `No`. If set to `true`, you'll get tld-specific translation ids generated per option. Optional. By default `false`.

##### Return Value

* domain field configuration array

##### Example

```php
".jobs" => [
    self::getYesNoField(".jobs", [
        "Name" => "Member of a HR Association",
        "Ispapi-Name" => "X-JOBS-HRANAME"
    ])
],
```

customized translation ids

```php
".xxx" => [
    self::getYesNoField(".xxx", [
        "Name" => "Non-Resolving Domain",
        "Description" => "xxxnonresolvingdomaindescr",
        "Ispapi-Name" => "X-XXX-NON-RESOLVING",
        "Options" => [ "0", "1" ]
    ], true),
]
```

### Deactivating a default WHMCS' field

Using our new way of additional fields integration ensure that **ALL** default additional domain fields of WHMCS are auto-removed if not reused by our own configuration.
So nothing we have to care about further on.

### Overriding a default WHMCS' field

1. Open `/resources/domain/dist.additionalfields.php` and search for the domain extension e.g. `.ca`
2. Copy the field configuration you want to customize into clipboard
3. Add it as tld-specific configuration as described before
4. Modify the configuration as desired by using static field generator methods - note:
   * key-value pairs you do not want to change, just remove - WHMCS defaults are then used
   * key-value pairs you want to modify, keep and customize them (see standard [WHMCS Documentation](https://docs.whmcs.com/Additional_Domain_Fields))

**NOTE:** There's no possibility to just deactivate a key-value pair, you can just overwrite them.

### Placeholders in Description

Sometimes, it is necessary to make translations dynamic for reuse. Therefore, we introduced the below placeholders which are being replaced automatically:

1. `{TAC}` will be replaced by the Terms and Conditions URL of that TLD. Ensure to have it introduced in static method `getTAC` of `AdditionalFields.class.php`
2. `{TLD}` will be replaced by the dotted TLD format.

This can be extended on demand, let us know if you need something.

### Local Presence / Trustee Service

As a Local Presence service is not free of charge and WHMCS does not offer a generic domain addon for this kind of service (see [feature request](https://requests.whmcs.com/topic/integrate-trustee-service-as-generic-domain-add-on)), you as reseller have to care about the configuration manually. Activating local presence service over additional domain fields could lead to losing money on your side when not having the Local Presence Service Fee included in the domain prices. Even though we at HEXONET offer our domain addons over additional domain fields (or so-called extension parameters), WHMCS handles these things over Domain Add-Ons like `Email Forwarding`, `DNS Management`. So the above feature request is about getting such an Add-On realized for Local Presence Service as it is perfect for exactly that purpose - allowing configuration of separate pricing and further configurations like a terms and conditions document.

Right now, you have to include the service fee in the domain extension price configuration when you want to use a local presence service to not lose money. This also means that customers who do not need a local presence service, would also pay a higher price even though not using that service.

Therefore, we decided to not activate the local presence service by default in our additional fields, to avoid any issues. If you still want to use a local presence service, follow these steps:

1. Add the local presence fee to all parts of the domain pricing of your TLD (registration, transfer) for every period.
2. Add below code example to the OVERRIDEFILDE, but specific to your TLD:

```php
$additionaldomainfields[".it"] = [
    [
        "Name" => "Local Presence",
        "Type" => "dropdown",
        "Options" => ",1|Use local presence service",
        "Description" => "(required if not residing in/belonging to a EU Member State)",
        "Default" => "",
        "Ispapi-Name" => "X-IT-ACCEPT-TRUSTEE-TAC"
    ]
];
```

**NOTE:**

* Always keep `Ispapi-Name` tld-dependent e.g. `X-EU-ACCEPT-TRUSTEE-TAC`.
* Our default implementation doesn't consider local presence service for the reasons mentioned above, therefore it can't be completely translated. If you need a solution for this, let us know.
* If it still doesn't work as expected, let us know.

TLDs known to support a trustee service over HEXONTE: .bayern, .berlin, .de, .eu, .forex, .it, .jp, .ruhr, .sg, AFNIC TLDs.

### ISPAPI specific configuration settings

#### Ispapi-CmdRemove [SINCE v3.0.0]

Used to conditionally remove parameters from final API command, see .dk for example.
The below example shows how to use this feature. In case the field's value is set to `INDIV`, parameter `OWNERCONTACT0ORGANIZATION` will be removed from final API Command.

```php
self::$additionalfieldscfg[self::$entity]["register"] = [
    // ...
    ".dk" => [
        [
            "Name" => "Registrant Legal Type",
            "Ispapi-CmdRemove" => [
                "INDIV" => "OWNERCONTACT0ORGANIZATION"
            ],
            // ...
        ]
    ]
];
```

or, in case the API command is using a nested array, the below way is also supported:

```php
self::$additionalfieldscfg[self::$entity]["register"] = [
    // ...
    ".dk" => [
        [
            "Name" => "Registrant Legal Type",
            "Ispapi-CmdRemove" => [
                "INDIV" => [
                    "OWNERCONTACT0" => "ORGANIZATION"
                ]
            ],
            // ...
        ]
    ]
];
```

#### Ispapi-Name

This property covers the so-called extension flag name of the additional domain field in our backend system API e.g.

```php
self::$additionalfieldscfg[self::$entity]["register"] = [
    // ...
    ".ca" => [
        [
            "Name" => "Legal Type",
            "LangVar" => "hxflaglegaltype",
            "Ispapi-Name" => "X-CA-LEGALTYPE",
            // ...
        ]
    ]
];
```

#### Ispapi-WhoisProtectable #### [SINCE v3.0.0]

**NOTE:** Can only be used in type section `whoisprivacy`.

Possibility to specify cases where the domain name can not benefit of WHOIS Privacy Protection.
e.g. for .CA it is only possible in case `Legal Type` has been configured to something that is considered as `Individual`.

```php
self::$additionalfieldscfg[self::$entity]["whoisprivacy"] = [
    // ...
    // We have here not an array of arrays. That's not an issue as for whois privacy service there will always just be one field.
    ".ca" => [
        "Description" => "catldwhoisoptoutdescr",
        "Ispapi-Name" => "X-CA-DISCLOSE",
        "Ispapi-WhoisProtectable" => [ "Legal Type" => "/^(CCT|RES|ABO|LGR)$/" ]
    ]
];
```

Specify `Name` of the field dependency as key in `Ispapi-WhoisProtectable` and a regular expression that can be used to validate against.
We will run over all keys provided and use their regular expressions provided to determine if the domain can be protected or not.

If you don't specify `Ispapi-WhoisProtectable` it depends on `ID Protection` service by default.

#### Ispapi-Prefill #### [SINCE v3.0.0]

Possibile values: `VAT-ID` and `DK-ID`.
Will prefill the field with client's data accordingly by setting `Default` to that value.

example for tax id prefilling:

```php
self::$additionalfieldscfg[self::$entity]["register"] = [
    // ...
    ".se" => [
        // ...
        [
            "Name" => "VAT ID",
            "Type" => "text",
            "LangVar" => "registrantvatid",
            "Ispapi-Name" => "X-NICSE-VATID",
            "Ispapi-Prefill" => "VAT-ID"
        ]
    ]
];
```

example for .DK user id prefilling:

```php
self::$additionalfieldscfg[self::$entity]["register"] = [
    // ...
    ".dk" => [
        // ...
        self::getContactIdentificationField("", [
            "Name" => "Registrant Contact",
            "Description" => "dktldcontactdescr",
            "Ispapi-Name" => "X-DK-REGISTRANT-CONTACT",
            "LangVar" => "dkregistrantcontact",
            "Ispapi-Prefill" => "DK-ID"
        ])
    ]
];
```

#### Ispapi-Options [REMOVED by v3.0.0]

Removed in favour of the more compact piped notation WHMCS now allows in property `options`.
Specify here values for dropdown lists / fields that should reach our backend system API. The index of the value provided here has to correspond to the one specified in property `Options`. e.g.:

```php
self::$additionalfieldscfg[self::$entity]["register"] = [
    // ...
    ".ca" => [
        [
            "Name" => "Legal Type",
            "LangVar" => "hxflaglegaltype",
            "Options" => implode(",", [
                "Corporation",
                "Canadian Citizen",
                // ...
                "Her Majesty the Queen"
            ]),
            "Ispapi-Options" => implode(",", [
                "CCO",
                "CCT",
                // ...
                "MAJ"
            ]),
            "Ispapi-Name" => "X-CA-LEGALTYPE",
        ]
    ]
];
```

Replace the above by

```php
self::$additionalfieldscfg[self::$entity]["register"] = [
    // ...
    ".ca" => [
        [
            "Name" => "Legal Type",
            "LangVar" => "hxflaglegaltype",
            "Options" => implode(",", [
                "CCO|Corporation",
                "CCT|Canadian Citizen",
                // ...
                "MAJ|Her Majesty the Queen"
            ]),
            "Ispapi-Name" => "X-CA-LEGALTYPE",
        ]
    ]
];
```

The values have to be provided as comma-separated string. For better visualization and maintenance, we just concatenate array entries with comma.

#### Ispapi-IgnoreForCountries [REMOVED by v3.0.0]

Specify a comma-separated list of countries for which this field should get ignored before sending it to our backend system API.

The list will then be compared to registrant's country which is provided by WHMCS in `$params["country"]` in the appropriate registrar module methods.

```php
self::$additionalfieldscfg[self::$entity]["register"] = [
    // ...
    ".it" => [
        [
            //...
            "Ispapi-IgnoreForCountries" => ['AT','BE','BG']
        ]
    ]
];
```

> In v3.x.x this parameter got removed as we deprecate [Local Presence Service](#local-presence-trustee-service) out of the box.
>
> WHMCS has to introduce it as Generic Domain Add-On to get it realized correctly. Further more, this additional field for Local Presence Service got saved in WHMCS, but ignored occasionally for API then.
> This doesn't make sense on top.

#### Ispapi-Replacements [REMOVED by v3.0.0]

If the parameter value is a valid key of this array, it gets replaced by the respective value. Indirect use via Ispapi-Options is preferred, direct use makes sense to map older values for backward compatibility.

> This config property has been removed in favour of `Options` setting.

#### Ispapi-Eval [REMOVED by v3.0.0]

PHP code to execute short before additional domain fields value (`$value`) will be applied to the backend system API command.
Provide it as _string_. Thought for manipulating that value manually for any reason.

> For security reasons (**even though no security issue was known**) and as the need for it deprecated, we decided to remove this setting. "Eval is evil!"
