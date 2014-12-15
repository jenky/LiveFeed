<?php

class Milano_LiveFeed_ViewAdmin_Option_List extends XenForo_ViewAdmin_Option_ListOptions
{
	public function renderHtml()
	{
		parent::renderHtml();

		$renderedOptionGroups = array();

		foreach ($this->_params['renderedOptions'] as $x => $renderedOption) 
		{
			$i = floor($x / 10);

            switch ($i) {

                case 0:
                    $title = new XenForo_Phrase('news_feed');
                    break;

                case 1:
                    $title = new XenForo_Phrase('LiveFeed');
                    break;
                
                default:
                    $title = '';
                    break;
            }

            $renderedOptionGroups[$i]['title'] = $title;

			$renderedOptionGroups[$i][$x] = $renderedOption;
		}

		$this->_params['renderedOptionGroups'] = $renderedOptionGroups;
	}
}