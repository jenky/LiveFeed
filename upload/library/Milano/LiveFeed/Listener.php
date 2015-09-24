<?php

class Milano_LiveFeed_Listener
{
	public static function loadRecentActivityController($class, array &$extend)
	{
		$extend[] = 'Milano_LiveFeed_ControllerPublic_RecentActivity';
	}

    public static function loadAccountController($class, array &$extend)
    {
        $extend[] = 'Milano_LiveFeed_ControllerPublic_Account';
    }

    public static function loadNewsFeedModel($class, array &$extend)
    {
        $extend[] = 'Milano_LiveFeed_Model_NewsFeed';
    }

    public static function loadNewsFeedViewPublic($class, array &$extend)
    {
        $extend[] = 'Milano_LiveFeed_ViewPublic_NewsFeed_View';
    }

    public static function loadUserDataWriter($class, array &$extend)
    {
        $extend[] = 'Milano_LiveFeed_DataWriter_User';
    }

    public static function loadOptionDataWriter($class, array &$extend)
    {
        $extend[] = 'Milano_LiveFeed_DataWriter_Option';
    }

    public static function loadOptionControllerAdmin($class, array &$extend)
    {
        $extend[] = 'Milano_LiveFeed_ControllerAdmin_Option';
    }

    public static function initDependencies(XenForo_Dependencies_Abstract $dependencies, array $data)
    {        
        XenForo_Template_Helper_Core::$helperCallbacks += array(
            'feedsource' => array('Milano_LiveFeed_Source', 'getSourceName'),
        );
    }
}