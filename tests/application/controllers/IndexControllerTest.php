<?php

class IndexControllerTest extends ControllerTestCase
{

	public function testIndexWithMessageAction()
	{
		$this->dispatch('/');
		$this->assertAction('index');
		$this->assertController('index');
	}

	public function testUserControllerShouldForwardToLogin()
	{
		$this->dispatch('/moje');
		$this->assertController('user');
		$this->assertAction('login');
//		$this->assertQueryCount('form#loginForm', 1);
	}

}
