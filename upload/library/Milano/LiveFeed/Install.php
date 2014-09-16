<?php

class Milano_LiveFeed_Install extends Milano_Common_Install
{
	/* Start auto-generated lines of code. */

	protected static function _getTables()
	{
		return array();
	}

	protected static function _getTablePatches()
	{
		return array('xf_user_option' => array('livefeed_widget_personal' => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\''));
	}

	/* End auto-generated lines of code. */
}