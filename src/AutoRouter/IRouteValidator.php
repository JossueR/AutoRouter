<?php

namespace AutoRouter;

interface IRouteValidator
{
    /**
     * Validate if the route can be loaded
     * @param $controller
     * @param $method
     * @return boolean
     */
    public function validate($controller, $method );
}