<?php
namespace Mio\TestUtils\Page\Membership;

use WebGuy;
use Mio\TestUtils\Helper\DummyUserInfo;

class SignupPage
{
    // include url of current page
    public static $uri='signup';
    public static $emailField="#edit-mail";
    public static $passwordField="#edit-pass";
    public static $salutation="#edit-fullname-salutation";
    public static $firstnameField="#edit-fullname-first-name";
    public static $lastnameField="#edit-fullname-last-name";
    public static $birthdayField="#edit-birthday-day";
    public static $birthmonthField="#edit-birthday-month";
    public static $birthyearField="#edit-birthday-year";
    public static $formSubmitButton = "#membership-signup-form input[type=submit]";


    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */


    /**
     * @var WebGuy;
     */
    protected $anonymous;

    public function __construct(WebGuy $I)
    {
        $this->anonymous = $I;
    }

    /**
     * @param DummyUserInfo $user_info
     * @return MembershipProfileSetupPage
     */
    public function signup(DummyUserInfo $user_info)
    {
        $I = $this->anonymous;

        $I->amOnPage(self::$uri);

        $I->selectOption(self::$salutation, $user_info->getSalutation());
        $I->fillField(self::$firstnameField, $user_info->getFirstname());
        $I->fillField(self::$lastnameField, $user_info->getLastname());

        $I->fillField(self::$emailField, $user_info->getEmail());
        $I->fillField(self::$passwordField, $user_info->getPassword());
        $I->selectOption(self::$birthdayField, $user_info->getBirthday());
        $I->selectOption(self::$birthmonthField, $user_info->getBirthmonth());
        $I->selectOption(self::$birthyearField, $user_info->getBirthyear());

        $I->wait(20);
        $I->click(self::$formSubmitButton);

        $I->seeInCurrentUrl('my-profile/setup/step');

        return $this;
    }
}
