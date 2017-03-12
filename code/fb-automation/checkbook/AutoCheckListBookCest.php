<?php

/**
 * Created by PhpStorm.
 * User: hieubq
 * Date: 27/02/2017
 * Time: 12:51
 */
use ImportData\ImportData;
class AutoCheckListBookCest {
    function goToFace(\WebGuy $I){
      $security = new ImportData();
      $I->amOnPage('/');
      $I->see('Facebook');
      // Login
      $I->fillField('#email',$security->user_name);
      $I->fillField('#pass', $security->password);
      $I->click('#loginbutton');
      $I->wait(3);
      $I->amOnPage('/groups/1615139492103847');
      $I->wait(3);
      $I->scrollToEnd();
    }
}