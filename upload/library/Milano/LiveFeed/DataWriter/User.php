<?php

class Milano_LiveFeed_DataWriter_User extends XFCP_Milano_LiveFeed_DataWriter_User
{
	protected function _getFields() 
	{
		$fields = parent::_getFields();
		
		$fields['xf_user_option']['livefeed_widget_personal'] = array(
			'type' => self::TYPE_BOOLEAN,
			'default' => 1
		);
		
		return $fields;
	}
}