<?php

class Milano_LiveFeed_ControllerPublic_RecentActivity extends XFCP_Milano_LiveFeed_ControllerPublic_RecentActivity
{
	public function actionIndex()
	{
		$response = parent::actionIndex();

		if ($response instanceof XenForo_ControllerResponse_View)
		{
			$response->params['liveFeed'] = $this->_input->filterSingle('live_feed', XenForo_Input::UINT);
			$response->params['source'] = $this->_input->filterSingle('source', XenForo_Input::STRING);
			$response->params['referer'] = $this->_input->filterSingle('referer', XenForo_Input::STRING);
		}

		return $response;
	}
}