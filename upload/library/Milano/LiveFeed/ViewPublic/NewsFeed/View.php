<?php

class Milano_LiveFeed_ViewPublic_NewsFeed_View extends XFCP_Milano_LiveFeed_ViewPublic_NewsFeed_View
{
	protected static $_newsFeedModel = null;

	public function renderJson()
	{
		parent::renderJson();

		if (isset($this->_params['liveFeed']))
		{
			$source = $this->_params['source'];

			switch ($source) 
			{
				case Milano_LiveFeed_Source::RECENT_ACTIVITY:
				case Milano_LiveFeed_Source::FORUM:
					// everyone news feed
					$liveFeed = self::_getNewsFeedModel()->getNewsFeed(array('news_feed_id' => array('>', $this->_params['liveFeed'])));
					break;

				case Milano_LiveFeed_Source::ACCOUNT:
					// personal news feed
					$visitor = XenForo_Visitor::getInstance();

					$liveFeed = self::_getNewsFeedModel()->getLiveFeedForUser($visitor->toArray(), $this->_params['liveFeed']);
					break;
				
				case Milano_LiveFeed_Source::MEMBER:
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
					break;

				default:
					break;
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

	protected function _getNewsFeedModel()
	{
		if (!self::$_newsFeedModel)
		{
			self::$_newsFeedModel = XenForo_Model::create('XenForo_Model_NewsFeed');
		}

		return self::$_newsFeedModel;
	}
}