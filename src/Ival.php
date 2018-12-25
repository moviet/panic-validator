<?php
/**
 * Panic - A simple php validation library
 *
 * @category   Validation
 * @package    Rammy Labs
 *
 * @author     Moviet
 * @license    http://www.opensource.org/licenses/mit-license.html MIT Public License
 *
 * @version    Build @@version@@
 */
namespace Moviet\Validator;

abstract class Ival
{	
	const MUST = 'Please Fill Out The Form';
	
	const MIN = ['id' => 'Karakter Terlalu Pendek',
                'en' => 'Character Too Short'];

	const MAX = ['id' => 'Karakter Terlalu Panjang',
                'en' => 'Character Too Long'];
	/**
	 * Default filter
	 */
	const FILTER = 
	[
        ':int'      => FILTER_VALIDATE_INT,
        ':float'    => FILTER_VALIDATE_FLOAT,
        ':url'      => FILTER_VALIDATE_URL,
        ':domain'   => FILTER_VALIDATE_DOMAIN,
        ':ip4'      => FILTER_VALIDATE_IP, FILTER_FLAG_IPV4,
        ':ip6'      => FILTER_VALIDATE_IP, FILTER_FLAG_IPV6,
        ':email'    => FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE
	];

	const DEFAULT_LENGTH = 0;

	const EMPTY_SET = null;

	/**
	 * Default pattern
	 */
	const PATTERN = 
	[
        ':num'          => '/^[0-9]*$/',
        ':phone'        => '/^\+[0-9]*$/',
        ':int'          => '/^[1-9]+[0-9]*$/',
        ':alpha'        => '/^[a-zA-Z]*$/',
        ':alphaNum'     => '/^[a-zA-Z0-9]*$/',
        ':alphaSpace'   => '/^[a-zA-Z +]*$/',
        ':alNumSpace'   => '/^[a-zA-Z0-9 ]*$/',
        ':query'        => '/^[a-zA-Z0-9_-+&=?\/]*$/',
        ':url'          => '/^[a-zA-Z0-9_-+&=#?:.\/]*$/',
        ':image'        => '/\.(jp?g|jpeg|png|gif|bmp)*$/',
        ':address'      => '/^[a-zA-Z0-9 \:\-\,\.\/\s]*$/',
        ':doc'          => '/\.(pdf|xls|doc|rtf|txt|ppt|pptx)*$/',
        ':subject'      => '/^[a-zA-Z0-9 \_\!\?\:\-\,\.\/\s]*$/i',
        ':email'        => '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{1,6}$/i',
        ':message'      => '/^[0-9a-zA-Z \%\#\-\_\!\@\,\.\?\/\s\<br\/\>.?\<br\/\>]*$/'
	];
}
