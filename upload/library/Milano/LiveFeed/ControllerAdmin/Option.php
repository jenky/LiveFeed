<?php

class Milano_LiveFeed_ControllerAdmin_Option extends XFCP_Milano_LiveFeed_ControllerAdmin_Option
{
	public function actionList()
	{
		$response = parent::actionList();

		if ($response instanceof XenForo_ControllerResponse_View)
		{
			if ($response->params['group']['group_id'] === 'newsfeed')
			{
				$response->viewName = 'Milano_LiveFeed_ViewAdmin_Option_List';
				$response->templateName = 'LiveFeed_option_list';
			}
		}

		return $response;
	}
}