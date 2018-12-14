<?php
/**
 * Panic - Testing
 * 
 * @author     Moviet (Rammy Labs)
 * @license    http://www.opensource.org/licenses/mit-license.html
 *
 * PHPUnit Test v7.5
 */
namespace Moviet\Testing;

use Moviet\Validator\Panic;
use \Exception;
use PHPUnit\Framework\TestCase;

class PanicTest extends TestCase
{
    public $valid;

    public function setUp()
    {
        $this->valid = new Panic;

        return $this->valid;
    }

    public function testCaseCannotBeEmpty()
    {
        $mock = $this->createMock(Panic::class);

        $mock->method('case')
            ->willReturn('object_validation');

        $this->assertEquals('object_validation', $mock->case());

        $null = empty($mock->case());

        $this->assertFalse($null);
    }

    public function testWithDefaultLang()
    {
        $mock = $this->createMock(Panic::class);

        $mock->expects(self::any())
             ->method('lang')
             ->with('En')
             ->will($this->returnValue('En'));

        $this->assertEquals('En',$mock->lang('En'));

        $null = is_null($mock->lang('En'));

        $this->assertFalse($null);
    }

    public function testMinMustBeNumeric()
    {
        $mock = $this->createMock(Panic::class);

        $mock->expects(self::any())
             ->method('min')
             ->will($this->returnValue(9));

        $this->assertEquals(9, $mock->min(9));

        $num = is_string($mock->min(9));

        $this->assertFalse($num);
    }

    public function testMaxMustBeNumeric()
    {
        $mock = $this->createMock(Panic::class);

        $mock->expects(self::any())
             ->method('max')
             ->will($this->returnValue(10));

        $this->assertEquals(10, $mock->max(10));

        $num = is_string($mock->max(10));

        $this->assertFalse($num);
    }

    public function testAuthMustBeNumeric()
    {
        $mock = $this->createMock(Panic::class);

        $mock->expects(self::any())
             ->method('auth')
             ->will($this->returnValue(8));

        $this->assertEquals(8, $mock->auth(8));

        $num = is_string($mock->auth(8));

        $this->assertFalse($num);
    }

    public function testRuleMustBeStringNotNull()
    {
        $mock = $this->createMock(Panic::class);

        $mock->expects(self::any())
             ->method('rule')
             ->will($this->returnValue(':num'));

        $this->assertEquals(':num', $mock->rule(':num'));

        $num = is_int($mock->rule(':num'));

        $null = !is_null($mock->rule(':num'));

        $this->assertFalse($num);

        $this->assertTrue($null);
    }

    public function testValidateWithEmptyCaseFailure()
    {
        $validate = $this->setUp()->case('') 
                                ->max(7)
                                ->rule(':alpha')
                                ->throw(['Must Be Alphabets']);

        $valid = $this->setUp()->confirm([$validate]);

        $this->assertFalse($valid);
    }
		
    public function testValidateWithLanguageIdFailure()
    {
        $validate = $this->setUp()->case('abcde') 
                                ->lang('Id')
                                ->min(2)
                                ->rule(':num')
                                ->throw(['Must Be Number']);

        $valid = $this->setUp()->confirm([$validate]);

        $this->assertFalse($valid);
    }

    public function testValidateMinimumRequirementFailure()
    {
        $validate = $this->setUp()->case('6289020200') // +6289020200 (+)
                                ->min(7)
                                ->rule(':phone')
                                ->throw(['Must Be Numeric']);

        $valid = $this->setUp()->confirm([$validate]);

        $this->assertFalse($valid);
    }

    public function testValidateMaximumRequirementFailure()
    {
        $validate = $this->setUp()->case('inject this')
                                ->min(2)
                                ->max(7)
                                ->rule(':alphaSpace')
                                ->throw(['Must Be Numeric']);

        $valid = $this->setUp()->confirm([$validate]);

        $this->assertFalse($valid);
    }

    public function testValidateMinPasswordLengthSuccess()
    {
        $validate = $this->setUp()->case('injection')
                                ->auth(8)
                                ->throw(['Password Minimum 8 Characters']);

        $valid = $this->setUp()->confirm([$validate]);

        $this->assertTrue($valid);
    }
		
    public function testValidateMinPasswordLengthFailure()
    {
        $validate = $this->setUp()->case('inject')
                                ->auth(8)
                                ->throw(['Password Minimum 8 Characters']);

        $valid = $this->setUp()->confirm([$validate]);

        $this->assertFalse($valid);
    }

    public function testValidationWithReturnValue()
    {
        $image = 'file.jpg';

        $validate = $this->setUp()->case($image)->rule(':image')->get();

        $this->assertEquals($validate, $image);
    }
		
    public function testSingleValidationFailure()
    {
        $string = 'Whatever';

        $validate = $this->setUp()->case($string)->rule(':num')->get();		

        $this->assertFalse($validate);
    }
    
    public function testSingleValidationWithEmptyValue()
    {
        $validate = $this->setUp()->case('')->rule(':num')->get();		

        $this->assertFalse($validate);
    }
		
    public function testValidationThrowSuccess()
    {
        $mock = $this->createMock(Panic::class);

        $mock->expects(self::any())
             ->method('throw')
             ->will($this->returnValue(null));

        $validate = $this->setUp()->case(12345)->rule(':num')->throw(['Must Be Numeric']);

        $valid = $this->setUp()->confirm([$validate]);

        $this->assertEquals($validate, $mock->throw(null));

        $this->assertTrue($valid);
    }

    public function testValidationThrowFailure()
    {
        $mock = $this->createMock(Panic::class);

        $mock->expects(self::any())
             ->method('throw')
             ->will($this->returnValue(null));

        $validate = $this->setUp()->case(12345)->rule(':alpha')->throw(['Must Be Alphabet']);

        $valid = $this->setUp()->confirm([$validate]);

        $this->assertNotEquals($validate, $mock->throw(null));

        $this->assertFalse($valid);
    }

    public function testValidationWithMatchPatternSuccess()
    {
        $email = 'test@email.com';

        $validate = $this->setUp()->match(':email',$email);

        $this->assertEquals($validate, $email);
    }
		
    public function testValidationWithMatchPatternFailure()
    {
        $email = 'test@email..com';

        $validate = $this->setUp()->match(':email',$email);

        $this->assertFalse($validate);
    }

    public function testValidationWithFilter()
    {
        $url = 'https://github.com/moviet/panic-validator';

        $validate = $this->setUp()->filter(':url',$url);

        $this->assertEquals($validate, $url);
    }

    public function testValidationWithYourOwnCustomPattern()
    {
        $myrule = $this->setUp()->case('My name is Rara')
                                ->lang('Id')
                                ->min(2)
                                ->max(20)
                                ->modify('/^[a-zA-Z 0-9]*$/')
                                ->throw(['Something wrong']);

        $this->assertNull($myrule);
    }
    
    public function testValidateUsingModifyPatternAndMassageFailure()
    {
        $validate = $this->setUp()->case('try this')
                                ->min(2)
                                ->max(20)
                                ->modify('/^[0-9]*$/')
                                ->throw(['Must be number']);

        $this->assertEquals('Must be number', $validate);
    }
		
    public function testValidateUsingModifyPatternFailure()
    {
        $validate = $this->setUp()->case('try this')
                                ->min(2)
                                ->max(20)
                                ->modify('/^[0-9]*$/')
                                ->throw(['Must be number']);

        $valid = $this->setUp()->confirm([$validate]);

        $this->assertFalse($valid);
    }
		
    public function testValidationWithFilterFailure()
    {
        $string = 'Hello Foo';

        $validate = $this->setUp()->filter(':int',$string);

        $this->assertFalse($validate);
    }

    public function testValidationWithCustomRegex()
    {
        $string = 'Hello Foo';

        $validate = $this->setUp()->draft('/^[a-zA-Z ]*$/',$string);

        $this->assertEquals($validate, $string);
    }
		
    public function testValidationWithDraftFailure()
    {
        $string = 'Hello Foo';

        $validate = $this->setUp()->draft('/^[0-9]*$/',$string);

        $this->assertFalse($validate);
    }

    public function testValidationCompare()
    {
        $string = 'Hello Foo';

        $validate = $this->setUp()->draft('/^[a-zA-Z ]*$/',$string);

        $equal = $this->setUp()->equal($validate, $string);

        $this->assertTrue($equal);
    }
		
    public function testValidationCompareFailure()
    {
        $string = 'Hello Foo';

        $validate = $this->setUp()->draft('/^[0-9]*$/',$string);

        $equal = $this->setUp()->equal($validate, $string);

        $this->assertFalse($equal);
    }

    public function testValidationCompareHashEqual()
    {
        $string = 'a23cabadaed728278badbe0100920ababdcbe204940faefan030';

        $validate = $this->setUp()->draft('/^[a-zA-Z0-9]*$/',$string);

        $equal = $this->setUp()->hashEqual($validate, $string);

        $this->assertTrue($equal);
    }
		
    public function testValidationCompareHashEqualFailure()
    {
        $validate = 'a23cabadaed728278badbe0100920ababdcbe204940faefan03';

        $string = 'a23cabadaed728278badbe0100920ababdcbe204940faefan030';

        $equal = $this->setUp()->hashEqual($validate, $string);

        $this->assertFalse($equal);
    }

    public function testValidationPasswordSuccess()
    {
        $password = 'This is my password';

        $validate = $this->setUp()->verify($password,'$2y$14$cSUK14J/Uz8WroJyeagwx.grUaKNX85NbxC3z96nLJQ0aItIjTBbe')->warn('Password Invalid');

        if ($this->setUp()->catch($validate)) {

            $this->assertTrue($validate);
        } 
    }
		
    public function testValidationPasswordFailure()
    {
        $password = 'This is not my password';

        $validate = $this->setUp()->verify($password,'$2y$14$cSUK14J/Uz8WroJyeagwx.grUaKNX85NbxC3z96nLJQ0aItIjTBbe')->warn('Password Invalid');

        $wrongPass = $this->setUp()->catch($validate);

        $this->assertFalse($wrongPass);
    }
		
    public function testValidationPasswordFailureMessage()
    {
        $password = 'This is not my password';

        $validate = $this->setUp()->verify($password,'$2y$14$cSUK14J/Uz8WroJyeagwx.grUaKNX85NbxC3z96nLJQ0aItIjTBbe')
        ->warn('Password Invalid');

        $this->assertEquals('Password Invalid',$validate); 
    }

    public function testValidationUsingTrustCallback()
    {
        $post = [12345,123456];

        $validate = $this->setUp()->case($post[0])->rule(':num')->throw(['Must Be Alphabet']);

        $validate2 = $this->setUp()->case($post[1])->rule(':num')->throw(['Must Be Alphabet']);

        $val = $this->setUp()->trust($post, [$validate,$validate2]);

        $this->assertEquals($val[0], $post[0]);
    }
		
    public function testValidationCustomModifyAndFailure()
    {
        $post = [12345,123456];

        $validate = $this->setUp()->case($post[0])->rule(':alpha')->throw(['Must Be Alphabet']);

        $validate2 = $this->setUp()->case($post[1])->rule(':num')->throw(['Must Be Alphabet']);

        $val = $this->setUp()->trust($post, [$validate,$validate2]);

        $this->assertFalse($val);
    }

    public function testEncodingNiceBase64()
    {
        $encode = $this->setUp()->base64('this is panic tests');

        $decode = $this->setUp()->pure64($encode);

        $this->assertEquals('this is panic tests', $decode);
    }

    public function testEncodingBase64Failure()
    {
        $encode = 'dGhpcyBpcyBwYW5pYyB0ZXN0cwww';

        $decode = $this->setUp()->pure64($encode);

        $this->assertNotEquals('this is panic tests', $decode);
    }

    public function testEncodingHtmlSuccess()
    {
        $encodeHtml = '<a href=https://github.com/moviet/panic-validator>panic validator</a><script>XSS</script>';

        $encode = $this->setUp()->htmlSafe($encodeHtml);

        $this->assertNotEquals($encodeHtml, $encode);
    }

    public function testDecodingHtmlSuccess()
    {
        $encodeHtml = '<a href=https://github.com/moviet/panic-validator>panic validator</a><script>XSS</script>';

        $encode = $this->setUp()->htmlSafe($encodeHtml);

        $decode = $this->setUp()->htmlRaw($encode);

        $this->assertSame($encodeHtml, $decode);
    }

    public function tearDown() {}
}
