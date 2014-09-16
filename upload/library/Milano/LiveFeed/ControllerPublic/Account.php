<?php

class Milano_LiveFeed_ControllerPublic_Account extends XFCP_Milano_LiveFeed_ControllerPublic_Account
{
	public function actionToggleLiveFeed()
	{
		$this->_assertPostOnly();

		$settings = $this->_input->filter(array(
			'livefeed_widget_personal' => XenForo_Input::UINT
		));

		if (!$this->_saveVisitorSettings($settings, $errors))
		{
			return $this->responseError($errors);
		}

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			$this->getDynamicRedirect()
		);
	}
}