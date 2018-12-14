<?php
/**
 * Panic - A simple php validation library
 *
 * @category   Validation
 * @package    Rammy Labs
 *
 * @author     Moviet
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT Public License
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
     * @param string case
     */
    protected $case;

    /**
     * @param string language
     */
    protected $lang;

    /**
     * @param int length
     */
    protected $min;

    /**
     * @param int length
     */
    protected $max;

    /**
     * @param string password
     */
    protected $auth;

    /**
     * @param string pattern
     */
    protected $rule;

    /**
     * @param string custom
     */
    protected $modify;

    /**
     * @param boolen password
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
     * @param string
     */
    public function case($string = [])
    {
        $this->case = $string;

        return $this;
    }

    /**
     * Create global languages
     * Default English
     * 
     * @param string
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
     * @param int number
     */
    public function min($param)
    {
        $this->min = is_numeric($param) ? $param : Ival::DEFAULT_LENGTH;

        return $this;
    }

    /**
     * Create maximum requirement
     * Default unlimited
     * 
     * @param int number
     */
    public function max($param)
    {
        $this->max = is_numeric($param) ? $param : Ival::EMPTY_SET;

        return $this;
    }

    /**
     * Generate password validation
     * Especial for password
     * 
     * @param int number
     */
    public function auth($param)
    {
        $this->auth = is_numeric($param) ? $param : Ival::EMPTY_SET;

        return $this;
    }

    /**
     * Create default pattern
     * 
     * @param string 
     */
    public function rule($param = null)
    {
        $this->rule[$this->case] = $param;

        return $this;
    }

    /**
     * Modify custom pattern
     * 
     * @param string pattern
     */
    public function modify($param = null)
    {
        $this->modify[$this->case] = $param;

        return $this;
    }

    /**
     * Generate conditional message
     * 
     * @return array message
     * 
     * @return false
     */
    protected function stress($string)
    {	
        $supress = count($string);

        if ($supress < self::COUNT_MESSAGE) {

            $supress = $string[self::COUNT_SIZE_CLEAN];

        } else {

            return false;
        }

        return $supress;				
    }	

    /**
     * Generate customize message and languages
     * Require field, can't be blank
     * If language doesn't set, use default
     * 
     * @param array string message
     * 
     * If characters doesn't match
     * @return string
     * 
     * If validation okay
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
     * @param array $param
     * @param array $validate
     * 
     * If doesn't match
     * @return false string
     * 
     * If validated
     * @return array value 
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
     * Generate confirmation
     * @param string
     * 
     * If validate doesn't match
     * @return false message
     * 
     * If validate okay
     * @return true value
     */
    public function confirm($param = [])
    {
        if (array_filter(array_values($param))) {

            return false;
        } 

        return true;
    }

    /**
     * Callback validation for single rule
     * 
     * If doesn't match
     * @return thrown
     * 
     * If success 
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
     * Create default pattern validation
     * @param string default pattern
     * 
     * @param string to validate
     * 
     * if validate doesn't match
     * @return false  
     * 
     * if characters match pattern
     * @return string
     */
    public function match($param, $string, $default = false)
    {
        if ($this->misMatch(Ival::PATTERN[$param], $string))	{

            return $default;
        }

        return $string;
    }

    /**
     * Create default filter validation
     * @param string to validate
     * 
     * @param string default filter
     * If validate match
     * @return string 
     * 
     * If characters doesn't match 
     * @return false
     */
    public function filter($param, $string, $default = false)
    {
        if ($this->filterVar($string, Ival::FILTER[$param]))	{

            return $default;
        }

        return $string;
    }

    /**
     * Create custom pattern
     * @param string any pattern
     * @param string validation
     * 
     * if validate doesn't match
     * @return false default
     * 
     * if characters match to pattern
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
     * Identical string comparation
     * @param string $param
     * @param string $string
     *
     * @return false default
     * @return true
     */
    public function equal($param, $string, $default = false)
    {
        if ($param === $string) {

            return true;
        }

        return $default;
    }

    /**
     * Hash equal string comparation
     * @param string $param
     * @param string $string
     *
     * @return false default
     * @return string
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
     * @return string comparable
     */
    protected function misMatch($param, $string)
    {
        return !preg_match($param, $string);
    }

    /**
     * Generate filter validation 
     *
     * @return string comparable
     */
    protected function filterVar($param, $string)
    {
        return !filter_var($param, $string);
    }

    /**
     * Password verification parameter
     * 
     * @param string password
     * 
     * @param string data 
     */
    public function verify($param, $data)
    {
        $this->verify = password_verify($param, $data);

        return $this;
    }

    /**
     * Create password message
     * 
     * @param string message
     * @return false show message
     * 
     * if validate goals
     * @return true
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
     * Verify password 
     * @param string
     * @return true
     * 
     * if doesn't match
     * @return false 
     */
    public function catch($param, $default = false)
    {
        if ($param !== true) {

            return $default;
        }			

        return true;
    }

    /**
     * Custom readable base64 encode
     * @param string
     * 
     * @return string
     */
    public function base64($string)
    {
        return rtrim(strtr(base64_encode($string), self::STR_REPLACE_PAD, self::STR_REPLACE_STRIP), self::STR_REPLACE_SMD);
    }

    /**
     * Custom readable base64 decode
     * @var string
     * 
     * @return string
     */
    public function pure64($string)
    {
        return base64_decode(str_pad(strtr($string, self::STR_REPLACE_STRIP, self::STR_REPLACE_PAD), strlen($string) % self::DEFAULT_PAD, self::STR_REPLACE_SMD, STR_PAD_RIGHT));
    }

    /**
     * Clean html entities characters
     * @param string html
     * 		 * 
     * @return string
     */
    public function htmlSafe($string)
    {
        return htmlentities($string, ENT_QUOTES | ENT_HTML5, self::ASCII_CHAR, false);
    }

    /**
     * Raw html entity characters
     * @var string output html
     * 		 * 
     * @return string
     */
    public function htmlRaw($string)
    {
        return html_entity_decode($string, ENT_QUOTES | ENT_HTML5, self::ASCII_CHAR);
    }
}
