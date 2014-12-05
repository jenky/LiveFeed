<?php

class Milano_LiveFeed_Widget
{
    public static function templateModification(array &$matches)
    {
        $replace = static::_appendTemplate($matches[0]);
        // print_r($matches);die;
        // $replace .= 'Hello';
        $matches[0] .= 'Hello';

        return $matches[0];
    }

    protected static function _appendTemplate($content)
    {
        $content = str_replace('<xen:if is="{$profilePosts}">', '<xen:include template="LiveFeed_widget" /> <xen:if is="{$profilePosts}">', $content);

        return $content;
    }
}