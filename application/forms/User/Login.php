<?php

class App_Form_User_Login extends Twitter_Bootstrap3_Form_Horizontal
{

	public function init()
	{
		$this->setMethod('post')
			->setAttrib('id', 'loginForm');

		$this->addElement('text', 'mail', array(
			'label'      => 'E-mailová adresa:',
			'required'   => true,
			'validators' => array('emailAddress'),
			'filters'    => array('StringtoLower', 'StripTags')
		));

		$this->addElement('password', 'password', array(
			'label'      => 'Heslo:',
			'required'   => true
		));

		$this->addElement('submit', 'submit', array(
			'ignore'     => true,
			'label'      => 'Odeslat'
		));
	}

}
