<?

final class PCHelperValidator {

    /**
     * 
     * @param string $username
     * @param string $error
     * @return boolean
     */
    public static function validateUsername($username, &$error = NULL) {
        $len = strlen($username);
        if ($len < 6) {
            $error = "Username is too short (min 6 chars)";
            return FALSE;
        } else if ($len > 60) {
            $error = "Username is too long (max 60 chars)";
            return FALSE;
        }
        $regex = "/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/";

        if (preg_match($regex, $username)) {
            return TRUE;
        }
        $error = "Invalid username: use only letters, numbers and undercores!";
        return FALSE;
    }

    /**
     * 
     * @param string $pwd
     * @param string $error
     * @return boolean
     */
    public static function validatePassword($pwd, &$error = NULL) {
        $len = strlen($pwd);
        if ($len < 6) {
            $error = "Password is too short (min 6 chars)";
            return FALSE;
        } else if ($len > 20) {
            $error = "Password is too long (max 20 chars)";
            return FALSE;
        }
        
        $regex =  $regex = "/^([_\$\-\@A-Za-z0-9])*$/";
        if (preg_match($regex, $pwd)) {
            return TRUE;
        }
        $error = "Invalid password: use only letters numbers and ( _ @ - $ )";
        return FALSE;
    }

    /**
     * 
     * @param string $email
     * @param string $error
     * @return boolean
     */
    public static function validateEmail($email, &$error = NULL) {
        if (filter_var(FILTER_VALIDATE_EMAIL)) {
            list($username, $domain) =  preg_split('/@/', $email);

            if (checkdnsrr($domain, 'MX')) {
                return TRUE;
            }

            $error = "Email domain is not reachable";
            return FALSE;
        }
        $error = "Email is not valid";
        return FALSE;
    }

    /**
     * 
     * @param string $comment
     * @param string $error
     * @return boolean|string
     */
    public static function validateComment($comment, &$error = NULL) {
        $len = strlen($comment);

        if ($len <= 5) {
            $error = "Comment is too short";
            return FALSE;
        }

        if ($len > PCModelReview::maxCommentLength) {
            $error = "Comment is too long";
            return FALSE;
        }

        $cleaned = PCHelperInputCleaner::cleanInputString($comment);
        $lenOther = strlen($cleaned);
        if ($len != $lenOther) {
            $error = "Comment is not valid";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 
     * @param string $name
     * @param string $error
     * @return boolean
     */
    public static function validateName($name, &$error = NULL) {
        $len = strlen($name);
        $cleaned = PCHelperInputCleaner::cleanInputString($name);
        $lenOther = strlen($cleaned);
        if ($len != $lenOther) {
            $error = "Name is not valid";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 
     * @param string $surname
     * @param string $error
     * @return boolean
     */
    public static function validateSurname($surname, &$error = NULL) {
        $len = strlen($surname);
        $cleaned = PCHelperInputCleaner::cleanInputString($surname);
        $lenOther = strlen($cleaned);
        if ($len != $lenOther) {
            $error = "Surname is not valid";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 
     * @param string $code
     * @returns boolean
     */
    public static function validateLanguageCode($code) {
        $var = array(
            'aa' => 'Afar',
            'ab' => 'Abkhaz',
            'ae' => 'Avestan',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'am' => 'Amharic',
            'an' => 'Aragonese',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Bashkir',
            'be' => 'Belarusian',
            'bg' => 'Bulgarian',
            'bh' => 'Bihari',
            'bi' => 'Bislama',
            'bm' => 'Bambara',
            'bn' => 'Bengali',
            'bo' => 'Tibetan Standard, Tibetan, Central',
            'br' => 'Breton',
            'bs' => 'Bosnian',
            'ca' => 'Catalan; Valencian',
            'ce' => 'Chechen',
            'ch' => 'Chamorro',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'cs' => 'Czech',
            'cu' => 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic',
            'cv' => 'Chuvash',
            'cy' => 'Welsh',
            'da' => 'Danish',
            'de' => 'German',
            'dv' => 'Divehi; Dhivehi; Maldivian;',
            'dz' => 'Dzongkha',
            'ee' => 'Ewe',
            'el' => 'Greek, Modern',
            'en' => 'English',
            'eo' => 'Esperanto',
            'es' => 'Spanish; Castilian',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fa' => 'Persian',
            'ff' => 'Fula; Fulah; Pulaar; Pular',
            'fi' => 'Finnish',
            'fj' => 'Fijian',
            'fo' => 'Faroese',
            'fr' => 'French',
            'fy' => 'Western Frisian',
            'ga' => 'Irish',
            'gd' => 'Scottish Gaelic; Gaelic',
            'gl' => 'Galician',
            'gn' => 'GuaranÃ­',
            'gu' => 'Gujarati',
            'gv' => 'Manx',
            'ha' => 'Hausa',
            'he' => 'Hebrew (modern)',
            'hi' => 'Hindi',
            'ho' => 'Hiri Motu',
            'hr' => 'Croatian',
            'ht' => 'Haitian; Haitian Creole',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'hz' => 'Herero',
            'ia' => 'Interlingua',
            'id' => 'Indonesian',
            'ie' => 'Interlingue',
            'ig' => 'Igbo',
            'ii' => 'Nuosu',
            'ik' => 'Inupiaq',
            'io' => 'Ido',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iu' => 'Inuktitut',
            'ja' => 'Japanese (ja)',
            'jv' => 'Javanese (jv)',
            'ka' => 'Georgian',
            'kg' => 'Kongo',
            'ki' => 'Kikuyu, Gikuyu',
            'kj' => 'Kwanyama, Kuanyama',
            'kk' => 'Kazakh',
            'kl' => 'Kalaallisut, Greenlandic',
            'km' => 'Khmer',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'ku' => 'Kurdish',
            'kv' => 'Komi',
            'kw' => 'Cornish',
            'ky' => 'Kirghiz, Kyrgyz',
            'la' => 'Latin',
            'lb' => 'Luxembourgish, Letzeburgesch',
            'lg' => 'Luganda',
            'li' => 'Limburgish, Limburgan, Limburger',
            'ln' => 'Lingala',
            'lo' => 'Lao',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'lv' => 'Latvian',
            'mg' => 'Malagasy',
            'mh' => 'Marshallese',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mr' => 'Marathi (Mara?hi)',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',
            'na' => 'Nauru',
            'nb' => 'Norwegian BokmÃ¥l',
            'nd' => 'North Ndebele',
            'ne' => 'Nepali',
            'ng' => 'Ndonga',
            'nl' => 'Dutch',
            'nn' => 'Norwegian Nynorsk',
            'no' => 'Norwegian',
            'nr' => 'South Ndebele',
            'nv' => 'Navajo, Navaho',
            'ny' => 'Chichewa; Chewa; Nyanja',
            'oc' => 'Occitan',
            'oj' => 'Ojibwe, Ojibwa',
            'om' => 'Oromo',
            'or' => 'Oriya',
            'os' => 'Ossetian, Ossetic',
            'pa' => 'Panjabi, Punjabi',
            'pi' => 'Pali',
            'pl' => 'Polish',
            'ps' => 'Pashto, Pushto',
            'pt' => 'Portuguese',
            'qu' => 'Quechua',
            'rm' => 'Romansh',
            'rn' => 'Kirundi',
            'ro' => 'Romanian, Moldavian, Moldovan',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',
            'sa' => 'Sanskrit (Sa?sk?ta)',
            'sc' => 'Sardinian',
            'sd' => 'Sindhi',
            'se' => 'Northern Sami',
            'sg' => 'Sango',
            'si' => 'Sinhala, Sinhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovene',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Swati',
            'st' => 'Southern Sotho',
            'su' => 'Sundanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',
            'ta' => 'Tamil',
            'te' => 'Telugu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Tswana',
            'to' => 'Tonga (Tonga Islands)',
            'tr' => 'Turkish',
            'ts' => 'Tsonga',
            'tt' => 'Tatar',
            'tw' => 'Twi',
            'ty' => 'Tahitian',
            'ug' => 'Uighur, Uyghur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            've' => 'Venda',
            'vi' => 'Vietnamese',
            'vo' => 'VolapÃ¼k',
            'wa' => 'Walloon',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'za' => 'Zhuang, Chuang',
            'zh' => 'Chinese',
            'zu' => 'Zulu',
        );
        
        return isset($var[strtolower($code)]);
        
    }

}