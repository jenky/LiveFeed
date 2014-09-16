<?php

class Milano_LiveFeed_Route_Prefix_Feed implements XenForo_Route_Interface
{
	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
	{
		return $router->getRouteMatch('Milano_LiveFeed_ControllerPublic_Feed', $routePath);
	}
}