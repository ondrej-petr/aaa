<?php

require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{

	protected $application;

	public function setUp()
	{
		$this->bootstrap = array($this, 'appBootstrap');
		parent::setUp();
	}

	public function appBootstrap()
	{
		$this->application = new Zend_Application(
			APPLICATION_ENV,
			APPLICATION_PATH . '/configs/application.ini'
		);
		$this->application->bootstrap();

		Zend_Session::$_unitTestEnabled = true;

		$this->getFrontController()->setParam('bootstrap', $this->application->getBootstrap());
	}

	//==========================================================================
	/**
	 * return App_Model_User
	 */
	protected function _loginLastInsertedUser()
	{
		$user = new App_Model_User();
		$users = $user->getAll();
		$this->assertGreaterThan(0, $users, 'V DB neni zadny user');

		$user = array_pop($users);
		Zend_Auth::getInstance()->getStorage()->write((object) $user->toArray());

		return $user;
	}

}
