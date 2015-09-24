<?php

class Milano_LiveFeed_Source
{
	const RECENT_ACTIVITY = 'recent_activity';
	const FORUM = 'forum';
	const MEMBER = 'member';
	const ACCOUNT = 'account';
	const UNKNOWN = 'unknown';

	public static function getSourceName($controllerName)
    {
        switch ($controllerName) 
        {
            case 'XenForo_ControllerPublic_RecentActivity':
                $name = static::RECENT_ACTIVITY;
                break;

            case 'XenForo_ControllerPublic_Forum':
                $name = static::FORUM;
                break;

            case 'XenForo_ControllerPublic_Member':
                $name = static::MEMBER;
                break;

            case 'XenForo_ControllerPublic_Account':
                $name = static::ACCOUNT;
                break;
            
            default:
                $name = static::UNKNOWN;
                break;
        }

        return trim($name);
    }
}