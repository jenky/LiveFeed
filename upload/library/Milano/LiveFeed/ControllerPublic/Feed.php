<?php

class Milano_LiveFeed_ControllerPublic_Feed extends XenForo_ControllerPublic_Abstract
{
	public function actionIndex()
	{
		$input = $this->_input->filter(array(
			'content_type' => XenForo_Input::STRING,
			'content_id' => XenForo_Input::UINT,
		));

		$newsFeedModel = $this->_getNewsFeedModel();
		$newsFeedHandler = $newsFeedModel->getNewsFeedHandlerFromCache($input['content_type']);

		var_dump($newsFeedHandler);die();

		return $this->responseView('Milano_LiveFeed_ViewPublic_Preview', 'LiveFeed_', array());
	}

	protected function _getNewsFeedModel()
	{
		return $this->getModelFromCache('XenForo_Model_NewsFeed');
	}
}