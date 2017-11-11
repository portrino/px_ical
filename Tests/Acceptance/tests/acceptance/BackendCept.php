<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('Open backend login without errors.');

$I->amOnPage('/typo3/index.php');

$I->see('TYPO3');
$I->seeElement('#typo3-index-php');
