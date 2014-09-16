<?php

class Milano_LiveFeed_ViewPublic_NewsFeed_View extends XFCP_Milano_LiveFeed_ViewPublic_NewsFeed_View
{
	protected static $_newsFeedModel = null;

	public function renderJson()
	{
		parent::renderJson();

		if (isset($this->_params['liveFeed']))
		{
			if (!XenForo_Application::getOptions()->useFriendlyUrls)
			{
				$this->_params['referer'] = str_replace('index.php?', '', $this->_params['referer']);
			}

			if ($this->_params['referer'] == $this->_getReplaceRoute('recent-activity/') || $this->_params['referer'] == '.')
			{
				// everyone news feed
				$liveFeed = self::_getNewsFeedModel()->getNewsFeed(array('news_feed_id' => array('>', $this->_params['liveFeed'])));
			}
			else if ($this->_params['referer'] == $this->_getReplaceRoute('account/news-feed'))
			{
				// personal news feed
				$visitor = XenForo_Visitor::getInstance();

				$liveFeed = self::_getNewsFeedModel()->getLiveFeedForUser($visitor->toArray(), $this->_params['liveFeed']);
			}
			else if (strpos($this->_params['referer'], $this->_getReplaceRoute('members/')) !== false)
			{
				// recent activity tab
				$parts = explode('/', $this->_params['referer']);
				$user = explode(XenForo_Application::URL_ID_DELIMITER, $parts[1]);

				if (isset($user[1]) && is_numeric($user[1]))
				{
					$userId = intval($user[1]);

					$conditions = array(
						'user_id' => $userId,
						'news_feed_id' => array('>', $this->_params['liveFeed'])
					);

					$liveFeed = self::_getNewsFeedModel()->getNewsFeed($conditions);
				}
			}

			if (!empty($liveFeed))
			{
				$this->_params = $liveFeed;
			}
		}

		$this->renderHtml();
		
		return XenForo_ViewRenderer_Json::jsonEncodeForOutput(array(
			'templateHtml' => $this->createTemplateObject('news_feed', $this->_params),
			'oldestItemId' => $this->_params['oldestItemId'],
			'newestItemId' => $this->_params['newestItemId'],
			'feedEnds' => $this->_params['feedEnds']
		));
	}

	protected function _getReplaceRoute($prefix)
	{
		$substr = false;
		if (substr($prefix, -1) == '/') 
		{
			$prefix = substr_replace($prefix , "", -1);
			$substr = true;
		}

		$routeFilters = $this->_getRouteFilters();

		if (isset($routeFilters[$prefix]))
		{
			foreach ($routeFilters[$prefix] as $route) 
			{
				list($from, $to) = XenForo_Link::translateRouteFilterToRegex(
					$route['find_route'], $route['replace_route']
				);

				return $to;
			}
		}

		return $substr ? $prefix . '/' : $prefix;
	}

	protected function _getRouteFilters()
	{
		if (XenForo_Application::isRegistered('routeFiltersOut'))
		{
			$routeFiltersOut = XenForo_Application::get('routeFiltersOut');
		}
		else
		{
			$routeFilters = XenForo_Model::create('XenForo_Model_RouteFilter')->rebuildRouteFilterCache();

			$routeFiltersOut = $routeFilters['out'];
		}

		return $routeFiltersOut;
	}

	protected function _getNewsFeedModel()
	{
		if (!self::$_newsFeedModel)
		{
			self::$_newsFeedModel = XenForo_Model::create('XenForo_Model_NewsFeed');
		}

		return self::$_newsFeedModel;
	}
}