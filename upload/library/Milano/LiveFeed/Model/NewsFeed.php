<?php

class Milano_LiveFeed_Model_NewsFeed extends XFCP_Milano_LiveFeed_Model_NewsFeed
{
	public function getNewsFeed(array $conditions = array(), $fetchOlderThanId = 0, array $viewingUser = null)
	{
		$parent = parent::getNewsFeed($conditions, $fetchOlderThanId, $viewingUser);

		$parent['newestItemId'] = $this->getNewestNewsFeedIdFromArray($parent['newsFeed']);

		return $parent;
	}

	public function getNewsFeedForUser(array $user, $fetchOlderThanId = 0, array $viewingUser = null)
	{
		$parent = parent::getNewsFeedForUser($user, $fetchOlderThanId, $viewingUser);

		$parent['newestItemId'] = $this->getNewestNewsFeedIdFromArray($parent['newsFeed']);

		return $parent;
	}

	public function getLiveFeedForUser(array $user, $fetchNewerThanId, array $viewingUser = null)
	{
		$this->standardizeViewingUserReference($viewingUser);

		$newsFeed = $this->getNewsFeedItemsForUser($user,
			array('news_feed_id' => array('>', $fetchNewerThanId))
		);
		
		$newsFeed = $this->fillOutNewsFeedItems($newsFeed, $viewingUser);

		$this->_cacheHandlersForNewsFeed($newsFeed);

		return array(
			'newsFeed' => $newsFeed,
			'newsFeedHandlers' => $this->_handlerCache,
			'oldestItemId' => $this->getOldestNewsFeedIdFromArray($newsFeed),
			'newestItemId' => $this->getNewestNewsFeedIdFromArray($newsFeed),
			'feedEnds' => (sizeof($newsFeed) == 0) // permissions make this hard to calculate
		);
	}

	public function getNewsFeedHandlerFromCache($contentType)
	{
		return $this->_getNewsFeedHandlerFromCache($contentType);
	}
}