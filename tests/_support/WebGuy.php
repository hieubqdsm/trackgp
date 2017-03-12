<?php
use Mio\TestUtils\Helper\DummyUserInfo;
use Mio\TestUtils\Page\Membership\LoginPage;
use Mio\TestUtils\Page\Membership\MembershipProfileSetupPage;
use Mio\TestUtils\Page\Membership\SignupPage;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class WebGuy extends \Codeception\Actor {
    use _generated\WebGuyActions;

    protected $user_info;

    public function __construct(\Codeception\Scenario $scenario) {
        parent::__construct($scenario);

        try {
            /** @var DummyUserInfo $currentUser */
            $this->user_info = \Codeception\Util\Fixtures::get('current_user_info');
        } catch (\RuntimeException $ex) {
            $this->user_info = NULL;
        }
    }

    /**
     * Define custom actions here
     */
    protected function signUp(DummyUserInfo $dummy_info) {
        $I = $this;

        // signup member
        $signup_page = new SignupPage($I);
        $signup_page->signup($dummy_info);

        // setup profile
        $membership_setup_page = new MembershipProfileSetupPage($I);
        $membership_setup_page->setupProfile($dummy_info);

        \Codeception\Util\Fixtures::add("current_user_info", $dummy_info);
    }

    public function loginAsMember() {
        if (empty($this->user_info)) {
            $dummy_info = DummyUserInfo::generateDummyUserInfo();
            $this->signUp($dummy_info);
            $this->user_info = \Codeception\Util\Fixtures::get('current_user_info');
        }

        $this->login($this->user_info->getEmail(), $this->user_info->getPassword());
    }

    /**
     * \Helper\DummyUserInfo
     * */
    public function getInfo() {
        return $this->user_info;
    }

    /**
     * Login with a available username and password
     */
    public function login($name, $password) {
        $I = $this;
        // if snapshot exists - skipping login
        if ($I->loadSessionSnapshot('login')) {
            return;
        }

        $loginPage = new LoginPage($I);

        $loginPage->login($name, $password);

        // saving snapshot
        $I->saveSessionSnapshot('login');
    }

    public function grabRandomTextFromSelectOption($select) {
        $texts      = $this->grabMultiple($select.' option');
        $random_key = array_rand($texts);

        return $texts[$random_key];
    }

    public function getCurrentUrl(){

        $js_url =<<<JS
            return jQuery(location).attr('href');
JS;
        $url= $this->executeJS($js_url);

        return $url;

    }

    public function getRandomIdFromCheckBoxesVisible($selector){
        $js_id= <<<JS
            var ids = {};
            jQuery('$selector .form-checkbox:visible').each(function(i) {
                ids[i] = jQuery(this).attr('id');
            });
            return ids;
JS;

        $id = $this->executeJS($js_id);

        return '#'.$id[array_rand($id,1)];
    }


    public function getInfoCheckboxesIsClicked($selector){
        $js_info = <<<JS
            var info ={};
            info['id_value'] = jQuery('$selector').val();
            info['label'] = jQuery('$selector').siblings('label').find('.term-name').text();
            return info;
JS;
        $info_element = $this->executeJS($js_info);

        return $info_element;

    }

    public function getOptionsVisible($selector){
        $js_options = <<<JS
            var options = {};
            jQuery('$selector .form-checkbox:visible').each(function(i) {
                options[i] = jQuery(this);             
            });
            return options;        
JS;
        $options = $this->executeJS($js_options);

        return $options;
    }

    public function countNumberOptionsCheckBoxesIsClicked($selector){
        $js_options =<<<JS
            return jQuery('$selector .form-checkbox:checked').length;
JS;

        $count = $this->executeJS($js_options);

        return $count;

    }




}
