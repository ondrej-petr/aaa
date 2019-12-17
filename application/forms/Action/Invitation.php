<?php

class App_Form_Action_Invitation extends Twitter_Bootstrap3_Form_Horizontal
{

	public function init()
	{
		$this->setMethod('post');

		$this->addElement('text', 'invitations', array(
			'label' => 'Pozvánky:',
			'required' => true
		));

		$this->addElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'Odeslat',
			'decorators' => array(
				array(),
				'ViewHelper'
			)
		));

		$this->addElement('button', 'btnBack', array(
			'label' => 'Přeskočit',
			'onclick' => "window.location='/akce/"
				. Zend_Controller_Front::getInstance()->getRequest()->getParam('actionId') . "'",
			'decorators' => array(
				array(),
				'ViewHelper'
			)
		));

		$this->addDisplayGroup(array('submit', 'btnBack'), 'buttons');
		$this->getDisplayGroup('buttons')->setDecorators(array(
			'formElements',
			array(array('div1' => 'HtmlTag'), array('tag' => 'div', 'class' => 'col-sm-offset-2 col-sm-10')),
			array(array('div2' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
		));
	}

}
