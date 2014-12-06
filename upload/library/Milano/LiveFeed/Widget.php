<?php

class Milano_LiveFeed_Widget
{
    protected static $_templateModificationModel;

    public static function templateModification(array $matches)
    {
        $template = static::findAndReplace($matches[0]);

        return $template;
    }

    public static function findAndReplace($content, $options = array())
    {        
        $widgetOptions = $options ? $options : XenForo_Application::getOptions()->LiveFeed_widget;

        if (!$widgetOptions['enabled'])
        {
            return $content;
        }

        $replace = '<xen:include template="LiveFeed_widget" />';

        switch ($widgetOptions['position']) 
        {
            case 'visitor_panel':
                $modification = array(
                    'find' => '<xen:if is="{$canViewMemberList}">',
                    'replace' => "{$replace} $0"
                );
                break;
            
            case 'online_users':
                $modification = array(
                    'find' => '<xen:if is="{$profilePosts}">',
                    'replace' => "{$replace} $0"
                );
                break;
                
            case 'profile_post':
                $modification = array(
                    'find' => '<!-- block: forum_stats -->',
                    'replace' => "{$replace} $0"
                );
                break;

            case 'forum_stats':
                $modification = array(
                    'find' => '<xen:include template="sidebar_share_page">',
                    'replace' => "{$replace} $0"
                );
                break;

            case 'share_page':
                $modification = array(
                    'find' => '</xen:hook>',
                    'replace' => "{$replace} $0"
                );
                break; 

            default:
                $modification = array();
                break;
        }

        $modification['action'] = 'str_replace';

        $templateModificationModel = static::_getTemplateModificationModel();

        return $templateModificationModel->applyTemplateModifications($content, array($modification), $status);
    }

    protected static function _getTemplateModificationModel()
    {
        if (!static::$_templateModificationModel)
        {
            static::$_templateModificationModel = XenForo_Model::create('XenForo_Model_TemplateModification');
        }

        return static::$_templateModificationModel;
    }
}