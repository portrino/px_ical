<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('To open frontend without any errors occured');

$I->amOnPage('');

$I->seeInTitle('New TYPO3 Console site: Home');
$I->see('TYPO3');
