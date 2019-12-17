<?php

class App_Form_Event_Gallery extends Twitter_Bootstrap3_Form_Horizontal
{

	public function init()
	{
		$this->setMethod('post')
			->setAttrib('id', 'galleryForm');

		$this->addElement('hidden', 'eventId', array(
			'required' => true
		));

		$this->addElement('file', 'img', array(
			'label' => 'Fotka:',
			'required' => true,
			'maxFileSize' => 8388608
		));

		$this->addElement('submit', 'submit', array(
			'ignore' => true,
			'label' => 'Odeslat'
		));
	}

}
