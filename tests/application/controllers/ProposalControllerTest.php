<?php

class ProposalControllerTest extends ControllerTestCase
{

	private $_action;

	private $_event;

	private $_user;

	//==========================================================================
	public function setUp()
	{
		parent::setUp();

		// vytvorim uzivatele
		$this->_user = new App_Model_User();
		if (!$this->_user->findByMail('proposal@test.cz')) {
			$this->_user->setPropsFromArray(array(
				'firstName' => 'Proposal',
				'lastName' => 'Test',
				'mail' => 'proposal@test.cz',
				'password' => 'dumpuser',
				'isActive' => 1
			));
			$this->_user->insert();
		}

		// najdu posledni akci, pokud zadna neexistuje, vytvorim novou
		$rows = App_Model_Action_Mapper::getInstance()->getAll(true, null, 'actionId DESC', 1);
		if (!count($rows)) {
			$action = new App_Model_Action();
			$action->setPropsFromArray(array(
				'userId' => $this->_user->getUserId(),
				'title' => 'testovací akce',
				'description' => '... vytvořená Unit testem',
				'interval' => 7
			));
			$action->insert();
			$rows = array($action);
		}
		$this->_action = array_shift($rows);
		try {
			$this->_action->addUser($this->_user->getUserId(), false, 1);
		} catch (Exception $e) {}

		// najdu 1. udalost teto akce, pokud neexistuje, vytvorim ji
		if (!$event = $this->_action->getEventByNo(1)) {
			$event = $this->_action->createNewEvent();
		}
		$this->_event = $event;
	}

	//==========================================================================
	public function testCreateProposals()
	{
		$this->_loginLastInsertedUser();

		$form = new App_Form_Proposal();

		// test data jako jednoho dne
		$postData = array(
			'eventId' => $this->_event->getEventId(),
			'dates' => '18.7.2016'
		);
		$this->assertTrue($form->isValid($postData));
		$this->request->setMethod('POST')
			->setPost($postData);
		$this->dispatch($this->getRequest()->getBaseUrl() . $this->_event->getUrl());
		$this->assertRedirectTo($this->getRequest()->getBaseUrl() . $this->_event->getUrl());

		$this->resetRequest()
			->resetResponse();
		$this->request->setMethod('GET')
			->clearPost();
		$this->dispatch($this->getRequest()->getBaseUrl() . $this->_event->getUrl());
		$this->assertQuery('div.alert-success');

		// test data jako intervalu bez casu
		$postData = array(
			'eventId' => $this->_event->getEventId(),
			'dates' => '18.7.2016 - 19.7.2016'
		);
		$this->assertTrue($form->isValid($postData));
		$this->resetRequest()
			->resetResponse();
		$this->request->setMethod('POST')
			->setPost($postData);
		$this->dispatch($this->_event->getUrl());

		// test data jako intervalu s casy
		$postData = array(
			'eventId' => $this->_event->getEventId(),
			'dates' => '18.07.2014 20:00 - 18.07.2014 21:00'
		);
		$this->assertTrue($form->isValid($postData));
		$this->resetRequest()
			->resetResponse();
		$this->request->setMethod('POST')
			->setPost($postData);
		$this->dispatch($this->_event->getUrl());
	}

	//==========================================================================
	public function testChangeProposals()
	{
		$this->_loginLastInsertedUser();
		$user = Zend_Registry::get('user');

		$proposals = $this->_event->getProposals();
		$proposal = array_pop($proposals);

		$postData = array(
			'pk' => array('proposalId' => $proposal->getProposalId(), 'userId' => $user->getUserId()),
			'value' => 0
		);
		$this->request->setMethod('POST')
			->setPost($postData);
		$this->dispatch($this->getRequest()->getBaseUrl() . '/ajax/change-proposal');
		$response = Zend_Json::decode($this->getResponse()->getBody());
		$this->assertTrue($response['success']);

		$vote = new App_Model_Vote();
		$this->assertTrue($vote->findById($proposal->getProposalId(), $user->getUserId()));
		$this->assertEquals(0, $vote->getVote());

		$this->_user->delete();
		Zend_Auth::getInstance()->clearIdentity();
	}


}
