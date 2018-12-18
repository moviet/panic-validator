<?php
/**
 * Panic - A simple php validation library
 *
 * @category   Validation
 * @package    Rammy Labs
 *
 * @author     Moviet
 * @license    MIT Public License
 *
 * @version    Build @@version@@
 */
namespace Moviet\Validator;

use Moviet\Validator\Ival;

/**
 * Start Panic
*/
class Panic
{	
  /**
     * @param string $case
     */
    protected $case;

    /**
     * @param string $lang
     */
    protected $lang;

    /**
     * @param int $min
     */
    protected $min;

    /**
     * @param int $max
     */
    protected $max;

    /**
     * @param string $auth
     */
    protected $auth;

    /**
     * @param string $rule
     */
    protected $rule;

    /**
     * @param string $modify
     */
    protected $modify;

    /**
     * @param string $verify
     */
    protected $verify;

    const COUNT_SIZE_CLEAN = 0;

    const COUNT_MESSAGE = 3;

    const TOTAL_COUNT_MSG = 2;

    const LANG_ID = 'id';

    const LANG_EN = 'en';

    const ASCII_CHAR = 'UTF-8';	

    const DEFAULT_PAD = 4;

    const STR_REPLACE_PAD = '+/';

    const STR_REPLACE_SMD = '=';

    const STR_REPLACE_STRIP = '-_';

    /**
     * Generate validation case
     * 
     * @param string $case
     */
    public function case($case = [])
    {
        $this->case = $case;

        return $this;
    }

    /**
     * Create global languages
     * Default English
     * 
     * @param string $language
     */
    public function lang($language)
    {
        $this->lang = $language;

        return $this;
    }

    /**
     * Create minimum requirement
     * Default unlimited
     * 
     * @param int $number
     */
    public function min($number)
    {
        $this->min = is_numeric($number) ? $number : Ival::DEFAULT_LENGTH;

        return $this;
    }

    /**
     * Create maximum requirement
     * Default unlimited
     * 
     * @param int $numeric
     */
    public function max($numeric)
    {
        $this->max = is_numeric($numeric) ? $numeric : Ival::EMPTY_SET;

        return $this;
    }

    /**
     * Generate password validation
     * Especial for password
     * 
     * @param int $numeric
     */
    public function auth($numeric)
    {
        $this->auth = is_numeric($numeric) ? $numeric : Ival::EMPTY_SET;

        return $this;
    }

    /**
     * Create default pattern
     * 
     * @param string $rule
     */
    public function rule($rule = null)
    {
        $this->rule[$this->case] = $rule;

        return $this;
    }

    /**
     * Modify custom pattern
     * 
     * @param string $modify
     */
    public function modify($modify = null)
    {
        $this->modify[$this->case] = $modify;

        return $this;
    }

    /**
     * Generate conditional message
     * 
     * @param string $string
     * @return array 
     */
    protected function stress($string)
    {	
        $supress = count($string);

        if ($supress < self::COUNT_MESSAGE) {
            $supress = $string[self::COUNT_SIZE_CLEAN];

        } 

        return $supress;				
    }	

    /**
     * Generate customize message and languages
     * Require field, can't be blank
     * If language doesn't set, use default
     * If characters doesn't match return message
     * 
     * @param string $message
     * @return string
     * @return null
     */
    public function throw($message = [])
    {	
        $lang = ($this->lang !== self::LANG_ID) ? self::LANG_EN : $this->lang;

        if (empty($this->case)) {
            return Ival::MUST;	
        } 

        if ($this->min > Ival::DEFAULT_LENGTH) { 
            if (strlen($this->case) < $this->min) {
                return Ival::MIN[$lang];
            }
        }

        if ($this->max > Ival::DEFAULT_LENGTH) { 
            if (strlen($this->case) > $this->max) {
                return Ival::MAX[$lang];
            }	
        }

        if (!is_null($this->auth) && $this->auth > Ival::DEFAULT_LENGTH) { 
            if (strlen($this->case) < $this->auth) {
                return $this->stress($message);

            } else {
                return Ival::EMPTY_SET;
            }
        }		

        if (!is_null($this->rule)) { 
            if ($this->misMatch(Ival::PATTERN[$this->rule[$this->case]], $this->case)) {
                return $this->stress($message);
            }	
        }

        if (!is_null($this->modify)) { 
            if ($this->misMatch($this->modify[$this->case], $this->case)) {
                return $this->stress($message);
            }	
        }

        return Ival::EMPTY_SET;	
    }		

    /**
     * Generate callback validation
     * If doesn't match return bool
     *
     * @param string $param
     * @param string $validate
     * @return string
     * @return bool 
     */
    public function trust($param = [], $validate = [])
    {
        if (empty(array_filter($validate))) {
            foreach ([$param] as $key => &$value) {
                return $key = $value;
            }
        }

        return false;
    }

    /**
     * Generate confirmation from validated
     * If validate doesn't match give bool
     *
     * @param string
     * @return bool
     */
    public function confirm($param = [])
    {
        if (array_filter(array_values($param))) {
            return false;
        } 

        return true;
    }

    /**
     * Generate validation for single rule
     * 
     * @return bool
     * @return string 
     */
    public function get()
    {
        if (empty($this->case)) {
            return false;
        }

        if ($this->misMatch(Ival::PATTERN[$this->rule[$this->case]], $this->case)) {
            return false;
        }

        return $this->case;
    }

    /**
     * Create pattern with default rule validation
     *
     * @param string $param
     * @param string $string
     * @return bool  
     * @return string
     */
    public function match($param, $string)
    {
        if ($this->misMatch(Ival::PATTERN[$param], $string)) {
            return false;
        }

        return $string;
    }

    /**
     * Create filter with default rule validation
     *
     * @param string $param
     * @param string $string
     * @return bool $default
     * @return string
     */
    public function filter($param, $string, $default = false)
    {
        if ($this->filterVar($string, Ival::FILTER[$param])) {
            return $default;
        }

        return $string;
    }

    /**
     * Create custom pattern without default rule
     *
     * @param string $pattern
     * @param string $string
     * @return bool $default
     * @return string
     */
    public function draft($pattern, $string, $default = false)
    {
        if ($this->misMatch($pattern, $string)) {
            return $default;
        }

        return $string;
    }

    /**
     * Create identical string comparation
     *
     * @param string $param
     * @param string $string
     * @return bool
     */
    public function equal($param, $string, $default = false)
    {
        if ($param === $string) {
            return true;
        }

        return $default;
    }

    /**
     * Create hash equal string comparation
     *
     * @param string $param
     * @param string $string
     * @return bool
     */
    public function hashEqual($param, $string, $default = false)
    {
        if (!hash_equals($param, $string)) {
            return $default;
        }

        return true;
    }

    /**
     * Generate pregmatch validation
     *
     * @param string $param
     * @param string $string
     * @return bool
     */
    protected function misMatch($param, $string)
    {
        return !preg_match($param, $string);
    }

    /**
     * Generate filter validation 
     *
     * @param string $param
     * @param string $string
     * @return bool
     */
    protected function filterVar($param, $string)
    {
        return !filter_var($param, $string);
    }

    /**
     * Password verification parameter
     * 
     * @param string $param
     * @param string $data 
     * @return array
     */
    public function verify($param, $data)
    {
        $this->verify = password_verify($param, $data);

        return $this;
    }

    /**
     * Create custom error message for password verify
     * 
     * @param string $param
     * @return bool
     * @return string
     */
    public function warn($param = [])
    {
        if ($this->verify !== false) {
            return true;

        } else {
            foreach ([$param] as $key => &$value) 
                return $value;
        }
    }

    /**
     * Verify and catch a password 
     *
     * @param string $param
     * @return bool
     */
    public function catch($param, $default = false)
    {
        if ($param !== true) {
            return $default;
        }			

        return true;
    }

    /**
     * Create custom readable base64 encode
     *
     * @param string $string 
     * @return string
     */
    public function base64($string)
    {
        return rtrim(strtr(base64_encode($string), self::STR_REPLACE_PAD, self::STR_REPLACE_STRIP), self::STR_REPLACE_SMD);
    }

    /**
     * Create reversable base64 decode
     *
     * @var string $string
     * @return string
     */
    public function pure64($string)
    {
        return base64_decode(str_pad(strtr($string, self::STR_REPLACE_STRIP, self::STR_REPLACE_PAD), strlen($string) % self::DEFAULT_PAD, self::STR_REPLACE_SMD, STR_PAD_RIGHT));
    }

    /**
     * Clean html entities characters
     *
     * @param string $string
     * @return string
     */
    public function htmlSafe($string)
    {
        return htmlentities($string, ENT_QUOTES | ENT_HTML5, self::ASCII_CHAR, false);
    }

    /**
     * Raw html entity characters
     *
     * @var string $string
     * @return string
     */
    public function htmlRaw($string)
    {
        return html_entity_decode($string, ENT_QUOTES | ENT_HTML5, self::ASCII_CHAR);
    }
}
