<?php
namespace Mio\TestUtils\Page\Membership;

use WebGuy;
use Mio\TestUtils\Helper\DummyUserInfo;

class MembershipProfileSetupPage
{
    // include url of current page
    public static $uri = 'my-profile/setup/step';
    public static $countryField = "#edit-country-tid";
    public static $cityField = "#edit-vn-city-tid";
    public static $phoneField = "#edit-phone-contact";
    public static $jobfunctionField ="#selectlistedit-functions";
    public static $industriesField ="#selectlistedit-expertise";
    public static $companyNameField = "#edit-company-tid";
    public static $jobLevelField = "#edit-level-tid";
    public static $jobTitleField = "#edit-title";
    public static $periodFromMonthField = "#edit-period-from-month";
    public static $periodFromYearField = "#edit-period-from-year";
    public static $periodToMonthField = "#edit-period-to-month";
    public static $periodToYearField = "#edit-period-to-year";

    public static $formSubmitButton = "//*[@id=\"edit-save-next\"]";
    public static $form="#profile-setup-step";

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */


    /**
     * @var \WebGuy;
     */
    protected $new_member;

    public function __construct(WebGuy $I)
    {
        $this->new_member = $I;
    }

    public function setupProfile(DummyUserInfo $user_info)
    {
        $I = $this->new_member;
        $I->seeInCurrentUrl('my-profile/setup/step');
        $I->see("fill up your professional profile.");
        $I->see("Anphabe profile helps you");
        $I->see("Find colleagues and friends");
        $I->see("Be found for new opportunities");
        $I->see("Promote your personal brand online");

        $I->selectOption(self::$countryField, $user_info->getCountryValueInt());
        $I->selectOption(self::$cityField, $user_info->getCityValueInt());
        $I->fillField(self::$phoneField, $user_info->getPhone());


        $I->selectOption(self::$jobfunctionField,$user_info->getJobFunctionValueInt());
        $I->selectOption(self::$industriesField, $user_info->getIndustryValueInts() );
        $I->fillField(self::$companyNameField, $user_info->getWorkAtCompanyName());
        $I->selectOption(self::$jobLevelField, $user_info->getJobLevel());
        $I->fillField(self::$jobTitleField, $user_info->getJobTitle());
        $I->selectOption(self::$periodFromMonthField, $user_info->getPeriodFromMonth());
        $I->selectOption(self::$periodFromYearField, $user_info->getPeriodFromYear());
        $I->selectOption(self::$periodToMonthField, $user_info->getPeriodToMonth());
        $I->selectOption(self::$periodToYearField, $user_info->getPeriodToYear());

        $I->submitForm(self::$form, array());
        //$I->click(self::$formSubmitButton);

        $I->seeInCurrentUrl('my-wall');
        $I->see("Recent Updates");
        $I->see("Contacts");

        $I->amOnPage('user/logout');


        return $this;
    }

}
