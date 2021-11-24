<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/WCS_Type.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/WCS_Group.php");

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Textfield.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Numericfield.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Textarea.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Passwordfield.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Dropdown.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Checkbox.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Radiobutton.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Emailfield.php");

    (new WCS_Type("akasse"))
        ->setArgs('label', 'A-Kasse')
        ->setArgs('public', true)
        ->setArgs('publicly_queryable', true)
        ->setArgs('show_ui', true)
        ->setArgs('show_in_rest', true) 
        ->setArgs('menu_position', 4)
        ->setArgs('menu_icon', 'dashicons-media-document')
        ->setArgs('delete_with_user', false)       
        ->addGroups([
            (new WCS_Group('price-tracking'))
                ->setName('Pricing & Tracking')
                ->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.')
                ->addFields([
                    (new WCS_Numericfield('price'))->setName('Price')->setVisibleColumn(true)->setLimits(0, 99999999999999)->setPosition(1,4),
                    (new WCS_Dropdown('price-currency'))->setName('Currency')->setPosition(5,7)->setDefaultValue('DKK')->setOptions([ 
                        'DKK' => 'Denmark Krone',                   
                        'ALL' => 'Albania Lek','AFN' => 'Afghanistan Afghani','ARS' => 'Argentina Peso','AWG' => 'Aruba Guilder','AUD' => 'Australia Dollar',
                        'AZN' => 'Azerbaijan New Manat','BSD' => 'Bahamas Dollar','BBD' => 'Barbados Dollar','BDT' => 'Bangladeshi taka','BYR' => 'Belarus Ruble','BZD' => 'Belize Dollar',
                        'BMD' => 'Bermuda Dollar','BOB' => 'Bolivia Boliviano','BAM' => 'Bosnia and Herzegovina Convertible Marka','BWP' => 'Botswana Pula',
                        'BGN' => 'Bulgaria Lev','BRL' => 'Brazil Real',
                        'BND' => 'Brunei Darussalam Dollar','KHR' => 'Cambodia Riel','CAD' => 'Canada Dollar','KYD' => 'Cayman Islands Dollar','CLP' => 'Chile Peso','CNY' => 'China Yuan Renminbi',
                        'COP' => 'Colombia Peso','CRC' => 'Costa Rica Colon','HRK' => 'Croatia Kuna','CUP' => 'Cuba Peso','CZK' => 'Czech Republic Koruna',
                        'DOP' => 'Dominican Republic Peso','XCD' => 'East Caribbean Dollar','EGP' => 'Egypt Pound','SVC' => 'El Salvador Colon','EEK' => 'Estonia Kroon','EUR' => 'Euro Member Countries',
                        'FKP' => 'Falkland Islands (Malvinas) Pound','FJD' => 'Fiji Dollar','GHC' => 'Ghana Cedis','GIP' => 'Gibraltar Pound','GTQ' => 'Guatemala Quetzal','GGP' => 'Guernsey Pound',
                        'GYD' => 'Guyana Dollar','HNL' => 'Honduras Lempira','HKD' => 'Hong Kong Dollar','HUF' => 'Hungary Forint','ISK' => 'Iceland Krona','INR' => 'India Rupee','IDR' => 'Indonesia Rupiah',
                        'IRR' => 'Iran Rial','IMP' => 'Isle of Man Pound','ILS' => 'Israel Shekel','JMD' => 'Jamaica Dollar','JPY' => 'Japan Yen','JEP' => 'Jersey Pound','KZT' => 'Kazakhstan Tenge',
                        'KPW' => 'Korea (North) Won','KRW' => 'Korea (South) Won','KGS' => 'Kyrgyzstan Som','LAK' => 'Laos Kip','LVL' => 'Latvia Lat','LBP' => 'Lebanon Pound',
                        'LRD' => 'Liberia Dollar','LTL' => 'Lithuania Litas','MKD' => 'Macedonia Denar','MYR' => 'Malaysia Ringgit','MUR' => 'Mauritius Rupee','MXN' => 'Mexico Peso',
                        'MNT' => 'Mongolia Tughrik','MZN' => 'Mozambique Metical','NAD' => 'Namibia Dollar','NPR' => 'Nepal Rupee','ANG' => 'Netherlands Antilles Guilder',
                        'NZD' => 'New Zealand Dollar','NIO' => 'Nicaragua Cordoba','NGN' => 'Nigeria Naira','NOK' => 'Norway Krone','OMR' => 'Oman Rial','PKR' => 'Pakistan Rupee',
                        'PAB' => 'Panama Balboa','PYG' => 'Paraguay Guarani','PEN' => 'Peru Nuevo Sol','PHP' => 'Philippines Peso','PLN' => 'Poland Zloty','QAR' => 'Qatar Riyal',
                        'RON' => 'Romania New Leu','RUB' => 'Russia Ruble','SHP' => 'Saint Helena Pound','SAR' => 'Saudi Arabia Riyal','RSD' => 'Serbia Dinar','SCR' => 'Seychelles Rupee',
                        'SGD' => 'Singapore Dollar','SBD' => 'Solomon Islands Dollar','SOS' => 'Somalia Shilling','ZAR' => 'South Africa Rand','LKR' => 'Sri Lanka Rupee','SEK' => 'Sweden Krona',
                        'CHF' => 'Switzerland Franc','SRD' => 'Suriname Dollar','SYP' => 'Syria Pound','TWD' => 'Taiwan New Dollar','THB' => 'Thailand Baht','TTD' => 'Trinidad and Tobago Dollar',
                        'TRY' => 'Turkey Lira','TRL' => 'Turkey Lira','TVD' => 'Tuvalu Dollar','UAH' => 'Ukraine Hryvna','GBP' => 'United Kingdom Pound','USD' => 'United States Dollar',
                        'UYU' => 'Uruguay Peso','UZS' => 'Uzbekistan Som','VEF' => 'Venezuela Bolivar','VND' => 'Viet Nam Dong','YER' => 'Yemen Rial','ZWD' => 'Zimbabwe Dollar'                        
                    ])->setDescription('Choose a currency')->setSuffix('€')->setPrefix('prefix'),
                    (new WCS_Dropdown('price-recurring'))->setName('Price Recurring')->setVisibleColumn(true)->setPosition(8,12)->setDefaultValue('fixed')->setOptions([
                        'fixed' => 'One-Time Payment',
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly'
                    ]),
                    (new WCS_Textfield('url'))->setName('Product URL')->setPosition(13,18)->setDescription('Choose a currency')->setSuffix('€')->setPrefix('prefix'),
                    (new WCS_Textfield('tracking-url'))->setName('Tracking URL')->setPosition(19,24)->setDescription('Choose a currency')->setSuffix('€')->setPrefix('prefix')
                ]),          
            (new WCS_Group('social-statistics'))
                ->setName('Social Profile Statistics')
                ->addFields([
                    (new WCS_Textfield('trustpilot-url'))->setName('Trustpilot URL')->setPosition(1, 6)->setDescription('Choose a currency')->setSuffix('€')->setPrefix('prefix'),
                    (new WCS_Numericfield('trustpilot-reviews'))->setName('Trustpilot reviews')->setLimits(0, 99999999999999)->setPosition(7, 9)->setDescription('Choose a currency')->setSuffix('€')->setPrefix('prefix'),
                    (new WCS_Numericfield('trustpilot-rating'))->setName('Trustpilot Rating')->setLimits(0, 100)->setPosition(10, 12),
                    (new WCS_Textfield('facebook-url'))->setName('Facebook URL')->setPosition(13, 18),
                    (new WCS_Numericfield('facebook-followers'))->setName('Facebook followers')->setLimits(0, 99999999999999)->setPosition(19, 21),
                    (new WCS_Textfield('instagram-url'))->setName('Instagram URL')->setPosition(25, 30),
                    (new WCS_Numericfield('instagram-followers'))->setName('Instagram followers')->setLimits(0, 99999999999999)->setPosition(31, 33),
                    (new WCS_Textfield('linkedin-url'))->setName('LinkedIn URL')->setPosition(37, 42),
                    (new WCS_Numericfield('linkedin-followers'))->setName('LinkedIn followers')->setLimits(0, 99999999999999)->setPosition(43, 45),
                ]),
            (new WCS_Group('contact-information'))
                ->setName('Contact Information')
                ->addFields([
                    (new WCS_Dropdown('country'))->setName('Country')->setPosition(1, 3)->setDefaultValue('DK')->setOptions([
                        "DK" => "Denmark",
                        "AF" => "Afghanistan","AL" => "Albania","DZ" => "Algeria","AS" => "American Samoa","AD" => "Andorra","AO" => "Angola","AI" => "Anguilla","AQ" => "Antarctica",
                        "AG" => "Antigua and Barbuda","AR" => "Argentina","AM" => "Armenia","AW" => "Aruba","AU" => "Australia","AT" => "Austria","AZ" => "Azerbaijan","BS" => "Bahamas",
                        "BH" => "Bahrain","BD" => "Bangladesh","BB" => "Barbados","BY" => "Belarus","BE" => "Belgium","BZ" => "Belize","BJ" => "Benin","BM" => "Bermuda","BT" => "Bhutan",
                        "BO" => "Bolivia","BA" => "Bosnia and Herzegovina","BW" => "Botswana","BV" => "Bouvet Island","BR" => "Brazil","IO" => "British Indian Ocean Territory",
                        "BN" => "Brunei Darussalam","BG" => "Bulgaria","BF" => "Burkina Faso","BI" => "Burundi","KH" => "Cambodia","CM" => "Cameroon","CA" => "Canada","CV" => "Cape Verde",
                        "KY" => "Cayman Islands","CF" => "Central African Republic","TD" => "Chad","CL" => "Chile","CN" => "China","CX" => "Christmas Island","CC" => "Cocos (Keeling) Islands",
                        "CO" => "Colombia","KM" => "Comoros","CG" => "Congo","CD" => "Congo, the Democratic Republic of the","CK" => "Cook Islands","CR" => "Costa Rica","CI" => "Cote D'Ivoire",
                        "HR" => "Croatia","CU" => "Cuba","CY" => "Cyprus","CZ" => "Czech Republic","DJ" => "Djibouti","DM" => "Dominica","DO" => "Dominican Republic",
                        "EC" => "Ecuador","EG" => "Egypt","SV" => "El Salvador","GQ" => "Equatorial Guinea","ER" => "Eritrea","EE" => "Estonia","ET" => "Ethiopia","FK" => "Falkland Islands (Malvinas)",
                        "FO" => "Faroe Islands","FJ" => "Fiji","FI" => "Finland","FR" => "France","GF" => "French Guiana","PF" => "French Polynesia","TF" => "French Southern Territories",
                        "GA" => "Gabon","GM" => "Gambia","GE" => "Georgia","DE" => "Germany","GH" => "Ghana","GI" => "Gibraltar","GR" => "Greece","GL" => "Greenland","GD" => "Grenada",
                        "GP" => "Guadeloupe","GU" => "Guam","GT" => "Guatemala","GN" => "Guinea","GW" => "Guinea-Bissau","GY" => "Guyana","HT" => "Haiti",
                        "HM" => "Heard Island and Mcdonald Islands","VA" => "Holy See (Vatican City State)","HN" => "Honduras","HK" => "Hong Kong","HU" => "Hungary","IS" => "Iceland",
                        "IN" => "India","ID" => "Indonesia","IR" => "Iran, Islamic Republic of","IQ" => "Iraq","IE" => "Ireland","IL" => "Israel","IT" => "Italy","JM" => "Jamaica",
                        "JP" => "Japan","JO" => "Jordan","KZ" => "Kazakhstan","KE" => "Kenya","KI" => "Kiribati","KP" => "Korea, Democratic People's Republic of",
                        "KR" => "Korea, Republic of","KW" => "Kuwait","KG" => "Kyrgyzstan","LA" => "Lao People's Democratic Republic","LV" => "Latvia","LB" => "Lebanon",
                        "LS" => "Lesotho","LR" => "Liberia","LY" => "Libyan Arab Jamahiriya","LI" => "Liechtenstein","LT" => "Lithuania","LU" => "Luxembourg","MO" => "Macao",
                        "MK" => "Macedonia, the Former Yugoslav Republic of","MG" => "Madagascar","MW" => "Malawi","MY" => "Malaysia","MV" => "Maldives","ML" => "Mali",
                        "MT" => "Malta","MH" => "Marshall Islands","MQ" => "Martinique","MR" => "Mauritania","MU" => "Mauritius","YT" => "Mayotte","MX" => "Mexico",
                        "FM" => "Micronesia, Federated States of","MD" => "Moldova, Republic of","MC" => "Monaco","MN" => "Mongolia","MS" => "Montserrat","MA" => "Morocco",
                        "MZ" => "Mozambique","MM" => "Myanmar","NA" => "Namibia","NR" => "Nauru","NP" => "Nepal","NL" => "Netherlands","AN" => "Netherlands Antilles",
                        "NC" => "New Caledonia","NZ" => "New Zealand","NI" => "Nicaragua","NE" => "Niger","NG" => "Nigeria","NU" => "Niue","NF" => "Norfolk Island",
                        "MP" => "Northern Mariana Islands","NO" => "Norway","OM" => "Oman","PK" => "Pakistan","PW" => "Palau","PS" => "Palestinian Territory, Occupied",
                        "PA" => "Panama","PG" => "Papua New Guinea","PY" => "Paraguay","PE" => "Peru","PH" => "Philippines","PN" => "Pitcairn","PL" => "Poland",
                        "PT" => "Portugal","PR" => "Puerto Rico","QA" => "Qatar","RE" => "Reunion","RO" => "Romania","RU" => "Russian Federation","RW" => "Rwanda",
                        "SH" => "Saint Helena","KN" => "Saint Kitts and Nevis","LC" => "Saint Lucia","PM" => "Saint Pierre and Miquelon",
                        "VC" => "Saint Vincent and the Grenadines","WS" => "Samoa","SM" => "San Marino","ST" => "Sao Tome and Principe","SA" => "Saudi Arabia","SN" => "Senegal",
                        "CS" => "Serbia and Montenegro","SC" => "Seychelles","SL" => "Sierra Leone","SG" => "Singapore","SK" => "Slovakia","SI" => "Slovenia","SB" => "Solomon Islands",
                        "SO" => "Somalia","ZA" => "South Africa","GS" => "South Georgia and the South Sandwich Islands","ES" => "Spain","LK" => "Sri Lanka","SD" => "Sudan","SR" => "Suriname",
                        "SJ" => "Svalbard and Jan Mayen","SZ" => "Swaziland","SE" => "Sweden","CH" => "Switzerland","SY" => "Syrian Arab Republic","TW" => "Taiwan, Province of China","TJ" => "Tajikistan",
                        "TZ" => "Tanzania, United Republic of","TH" => "Thailand","TL" => "Timor-Leste","TG" => "Togo","TK" => "Tokelau","TO" => "Tonga","TT" => "Trinidad and Tobago","TN" => "Tunisia",
                        "TR" => "Turkey","TM" => "Turkmenistan","TC" => "Turks and Caicos Islands","TV" => "Tuvalu","UG" => "Uganda","UA" => "Ukraine","AE" => "United Arab Emirates",
                        "GB" => "United Kingdom","US" => "United States","UM" => "United States Minor Outlying Islands","UY" => "Uruguay","UZ" => "Uzbekistan","VU" => "Vanuatu",
                        "VE" => "Venezuela","VN" => "Viet Nam","VG" => "Virgin Islands, British","VI" => "Virgin Islands, U.s.","WF" => "Wallis and Futuna","EH" => "Western Sahara",
                        "YE" => "Yemen","ZM" => "Zambia","ZW" => "Zimbabwe"
                    ]),
                    (new WCS_Textfield('street'))->setName('Street Name')->setPosition(13, 15),
                    (new WCS_Textfield('street-number'))->setName('Street Number')->setPosition(25, 27),
                    (new WCS_Textfield('city'))->setName('City')->setPosition(37, 39),
                    (new WCS_Textfield('postalcode'))->setName('Postal Code')->setPosition(49, 51),
                    (new WCS_EmailField('contact-email'))->setName('Contact E-mail')->setVisibleColumn(true)->setPosition(61, 63),
                    (new WCS_TextField('contact-phone'))->setName('Contact Phone')->setVisibleColumn(true)->setPosition(73, 75),

                    // Monday open and closing time
                    (new WCS_Numericfield('monday-open-hr'))->setName("Mon. Open Hour")->setLimits(0, 23)->setPosition(5, 6),
                    (new WCS_Numericfield('monday-open-min'))->setName("Mon. Open Minute")->setLimits(0, 59)->setPosition(7, 8),
                    (new WCS_Numericfield('monday-close-hr'))->setName("Mon. Close Hour")->setLimits(0, 23)->setPosition(9, 10),
                    (new WCS_Numericfield('monday-close-min'))->setName("Mon. Close Minute")->setLimits(0, 59)->setPosition(11, 12),

                    // Thursday open and closing time
                    (new WCS_Numericfield('tuesday-open-hr'))->setName("Tue. Open Hour")->setLimits(0, 23)->setPosition(17, 18),
                    (new WCS_Numericfield('tuesday-open-min'))->setName("Tue. Open Minute")->setLimits(0, 59)->setPosition(19, 20),
                    (new WCS_Numericfield('tuesday-close-hr'))->setName("Tue. Close Hour")->setLimits(0, 23)->setPosition(21, 22),
                    (new WCS_Numericfield('tuesday-close-min'))->setName("Tue. Close Minute")->setLimits(0, 59)->setPosition(23, 24),

                    // Wednesday open and closing time
                    (new WCS_Numericfield('wednesday-open-hr'))->setName("Wed. Open Hour")->setLimits(0, 23)->setPosition(29, 30),
                    (new WCS_Numericfield('wednesday-open-min'))->setName("Wed. Open Minute")->setLimits(0, 59)->setPosition(31, 32),
                    (new WCS_Numericfield('wednesday-close-hr'))->setName("Wed. Close Hour")->setLimits(0, 23)->setPosition(33, 34),
                    (new WCS_Numericfield('wednesday-close-min'))->setName("Wed. Close Minute")->setLimits(0, 59)->setPosition(35, 36),

                    // Thursday open and closing time
                    (new WCS_Numericfield('thursday-open-hr'))->setName("Thur. Open Hour")->setLimits(0, 23)->setPosition(41, 42),
                    (new WCS_Numericfield('thursday-open-min'))->setName("Thur. Open Minute")->setLimits(0, 59)->setPosition(43, 44),
                    (new WCS_Numericfield('thursday-close-hr'))->setName("Thur. Close Hour")->setLimits(0, 23)->setPosition(45, 46),
                    (new WCS_Numericfield('thursday-close-min'))->setName("Thur. Close Minute")->setLimits(0, 59)->setPosition(47, 48),

                    // Friday open and closing time
                    (new WCS_Numericfield('friday-open-hr'))->setName("Fri. Open Hour")->setLimits(0, 23)->setPosition(53, 54),
                    (new WCS_Numericfield('friday-open-min'))->setName("Fri. Open Minute")->setLimits(0, 59)->setPosition(55, 56),
                    (new WCS_Numericfield('friday-close-hr'))->setName("Fri. Close Hour")->setLimits(0, 23)->setPosition(57, 58),
                    (new WCS_Numericfield('friday-close-min'))->setName("Fri. Close Minute")->setLimits(0, 59)->setPosition(59, 60),

                    // Saturday open and closing time
                    (new WCS_Numericfield('saturday-open-hr'))->setName("Sat. Open Hour")->setLimits(0, 23)->setPosition(65, 66),
                    (new WCS_Numericfield('saturday-open-min'))->setName("Sat. Open Minute")->setLimits(0, 59)->setPosition(67, 68),
                    (new WCS_Numericfield('saturday-close-hr'))->setName("Sat. Close Hour")->setLimits(0, 23)->setPosition(69, 70),
                    (new WCS_Numericfield('saturday-close-min'))->setName("Sat. Close Minute")->setLimits(0, 59)->setPosition(71, 72),

                    // Sunday open and closing time
                    (new WCS_Numericfield('sunday-open-hr'))->setName("Sun. Open Hour")->setLimits(0, 23)->setPosition(77, 78),
                    (new WCS_Numericfield('sunday-open-min'))->setName("Sun. Open Minute")->setLimits(0, 59)->setPosition(79, 80),
                    (new WCS_Numericfield('sunday-close-hr'))->setName("Sun. Close Hour")->setLimits(0, 23)->setPosition(81, 82),
                    (new WCS_Numericfield('sunday-close-min'))->setName("Sun. Close Minute")->setLimits(0, 59)->setPosition(83, 84),

                ]),
            (new WCS_Group('akasse-attributes'))
                ->setName('A-kasse Attributes')
                ->addFields([                    
                    (new WCS_Dropdown('students-free'))->setName('Free for students')->setDefaultValue('no')->setOptions(['yes' => 'All students free', 'no' => 'Students are not free'])->setPosition(1, 4),
                    (new WCS_Dropdown('availability'))->setName('Availability')->setDefaultValue('open')->setOptions(['open' => 'Open for everybody', 'restricted' => 'Restricted to specific sectors'])->setPosition(5, 8),
                    (new WCS_Numericfield('customers'))->setName("Number of Customers")->setLimits(0, 99999999999999)->setPosition(9, 12),
                    (new WCS_Textfield('highlight-one'))->setName('Hightlight 1')->setPosition(13, 16),
                    (new WCS_Textfield('highlight-two'))->setName('Hightlight 2')->setPosition(17, 20),
                    (new WCS_Textfield('highlight-three'))->setName('Hightlight 3')->setPosition(21, 24)
                ])

        ])
        ->hook();        
