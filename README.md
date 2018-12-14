Panic - A simple php validation and sanitation
======================================================
[![Build Status](https://travis-ci.org/moviet/panic-validator.svg?branch=master)](https://travis-ci.org/moviet/panic-validator)
[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://doge.mit-license.org)
[![Usage](https://img.shields.io/badge/usage-easy-ff69b4.svg)](https://github.com/moviet/panic-validator)
[![codecov](https://codecov.io/gh/moviet/panic-validator/branch/master/graph/badge.svg)](https://codecov.io/gh/moviet/panic-validator)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/fe6415f880494880b69cf574d9248f9d)](https://www.codacy.com/app/moviet/panic-validator?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=moviet/panic-validator&amp;utm_campaign=Badge_Grade)

Panic validator is adobted for simple lovely and easy like **_common_** validation in php

There are **no hardcoded** for simply usage, no weight and pretty useful

## Preparement
* Composer 
* [Clone Github](https://github.com/moviet/panic-validator.git)
* [Manual Download](https://github.com/moviet/panic-validator/archive/master.zip)

## Quick Start

#### Installations
```
composer require "moviet/panic-validator"
```

## Features

* Simply Validation
* Custom Warning
* Custom Verification
* Simple Filtering
* Modify Pattern
* Validate Password
* Sanitize Html
* &#10070; **Plus Bonus** &#10070;

## Common Usage

### Simply Validation

* You can validate with fast direct resolve example
  ```php
  require __DIR__ . '/vendor/autoload.php';

  use Moviet\Validator\Panic;
  use Moviet\Validator\Ival;

  $panic = new Panic;

  $nom = 'Smile'; 

  $getnom = $panic->case($nom)
                  ->rule(':alpha')
                  ->get(); 

  // var_dump : Smile
  ```
  The above will return value, if doesn't match will give **_thrown_** _exception_

* Or maybe you enjoy quietly with something like this
   ```php
   $data = $panic->match(':doc','mydocument.xls');

   // Output : mydocument.xls
   ```
   The above will return value, if doesn't match will **_return false_**

### Custom Warning

* Validate using your own message
  ```php
  $request = $_POST['String']; 

  $post = $panic->case($request)
                ->rule(':alphaNumSpace')
                ->throw(['Do not burn your finger, invalid !!']);
  ```

* Validate using limit and custom language
   ```php
  $somePost = $panic->case($_POST['String'])
                    ->lang('En') // Optional
                    ->min(3)
                    ->max(100)
                    ->rule(':alphaSpace')
                    ->throw(['Please follow wakanda alphabets']);

   ```

* Validate using empty case
   ```php
   $anyPost = $panic->case(/*..Empty..*/)
                    ->rule(':alpha')
                    ->throw(['~ Gunakan angka atau spasi']);

  // Output : 'Please Fill Out The Form'
  ```
  **Notes** : 
  > You can't validate an empty value, by **default** the field does not allow **_empty_** chunks

### Custom Verification

* You can validate for example **_html form_** like simply below
   ```php
   $validUser = $panic->case($_POST['username'])
                      ->rule(':email')
                      ->throw(['Your username may error !!']);

   $validPass = $panic->case($_POST['password'])
                      ->auth(8)
                      ->throw(['Password minimum 8 characters']);

   $data = [$validUser, $validPass];

   if ($panic->confirm($data) !== false) {

      // $_POST['username']
      // $_POST['password']
      // ...
   }
   ```   

* Render on your own template **_html form_** example
   ```html
   <form method="POST" action="login.php" name="login">
   <table>
   <tbody>

   <tr><td>
   <?php if (!$validUser) ?>
   <div class="title_form">Username *
   <label for='username' class='error'><?= $validUser; ?></label></div>
   <input type="text" placeholder="Enter username" name="username" class="form_login" value="" required>
   </td></tr>

   <tr><td>
   <?php if (!$validPass) ?>
   <div class="title_form">Password *
   <label for='password' class='error'><?= $validPass; ?></label></div>
   <input type="text" placeholder="Enter password" name="password" class="form_login" value="" required>
   </td></tr>

   </tbody>
   </table>
   </form>
   ``` 

* If the above doesn't look so nice, you can **_trust_** with new endorse
   ```php
   $product = $panic->case($_GET['product'])
                    ->rule(':alphaNumSpace')
                    ->throw(['Do not only look, Please bo bo boy !!']);

   $tokenId = $panic->case($_GET['token_id'])
                    ->rule(':int')
                    ->throw(['Your token of course, invalid !!']);

   $validate = [$product, $tokenId];

   $trust = $panic->trust([$_GET], $validate);

   $trust[0] // Equivalent $product
   $trust[1] // Equivalent $tokenId
   Next Next...
   ```  

### Simple Filtering

* We use native **_filter_** functions look like below

   ```php
   $url = 'https://github.com/moviet/panic-validator';

   $data = $panic->filter(':url', $url); 

   // Output : https://github.com/moviet/panic-validator
    ```

### Modify Pattern

* You can modify with **_modify_** like _your own rules_
   ```php
   $myrule = $panic->case('My name is yoyo')
                   ->min(2)
                   ->max(20)
                   ->modify('/^[a-zA-Z 0-9]*$/')
                   ->throw(['What the hell something wrong ??']);

   // Return Boolen
   ```

* You may want to **_draft_** and **_match_** for non messenger
   ```php
   $string = 'Thanks you';

   $data = $panic->draft('/^[a-zA-Z@0-9_1-`-02-^"?>/+\//./]*$/', $string); 

   // Output : Thanks you

   $string = 'Thanks Match';

   $data = $panic->match(':message', $string); 

   // Output : Thanks Match
    ```

### Validate Password

* We put a simply for native **_password hash_** validation

   ```php
   $data = $panic->verify($_POST['password'], 'DataPassword')
                 ->warn('Do not type password, please use Abcde !!'); 

   if ($panic->catch($data)) {

      // $_POST['password']
   }

   // Wrong Output => Do not type password, please use Abcde !!
   ```

### Sanitize Html

* You can sanitize with our little pony for simply safe html as one packet

   ```php
   $stringHtml = '<script> If this is XSS </script>';

   $filterHtml = $panic->htmlSafe($stringHtml); 

   $html = $panic->htmlRaw($filterHtml); 

   // Output : <script> If this is XSS </script>
   ```

### Plus Bonus

* Whatever usage a custom **_clean base64_** encode may interesting

   ```php
   $string = 'And thanos will go on';

   $encode = $panic->base64($string); 

   // Output : aOAIOAIHJSDH837287ksasjka983jsdhdsfsJHJAdsfd34dfSfb

   $decode = $panic->pure64($encode); 

   // Output : And thanos will go on
   ```

### Patterns

| Attributes     | Format                       | 
| -------------- |:-----------------------------| 
| :num           | 0-9                          |
| :phone         | +081991988xx                 | 
| :int           | 0-9                          | 
| :alpha         | alphabets                    |
| :alphaNum      | Alphabets Number             | 
| :alphaSpace    | Alphabets Spaces             |
| :query         | Http UrlQuery                |
| :url           | Url                          |
| :image         | Jpg, Jpeg, Png, Bmp, Gif     |
| :doc           | pdf,xls,doc,rtf,txt,ppt,pptx |
| :address       | Normal Address               |
| :subject       | Email Subject                |
| :email         | Email Address                |
| :message       | Simple message characters    |

### Filters

| Attributes     | Native                       | 
| -------------- |:-----------------------------| 
| :int           | Filter Validate Integer      |
| :float         | Filter Validate Float        | 
| :url           | Filter Validate Url          | 
| :domain        | Filter Validate Domain       |
| :ip4           | Filter Validate IP 4         | 
| :ip6           | Filter Validate IP 6         |
| :email         | Filter Validate Email        |


## License

`Moviet/panic-validator` is released under the MIT public license.
