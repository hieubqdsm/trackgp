<?php
namespace Mio\TestUtils\Page\Membership;

use WebGuy;

class LoginPage
{
    public static $uri='/user/login';
    public static $usernameField = '#edit-name';
    public static $passwordField = '#edit-pass';
    public static $formSubmitButton = "#user-login input[type=submit]";

    /**
     * @var \WebGuy;
     */
    protected $anonymous;

    public function __construct(WebGuy $I)
    {
        $this->anonymous = $I;
    }

    public function login($name, $pass){
        $I = $this->anonymous;

        $I->amOnPage(self::$uri);
        $I->fillField(self::$usernameField, $name);
        $I->fillField(self::$passwordField, $pass);
        $I->click(self::$formSubmitButton);

        $I->seeInCurrentUrl('/my-wall');


        return $this;
    }

}
