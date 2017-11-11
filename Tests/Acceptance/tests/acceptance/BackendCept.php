<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('To open backend login without errors.');

$I->amOnPage('/typo3/');

$I->see('TYPO3');
