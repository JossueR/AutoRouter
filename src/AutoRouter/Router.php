<?php

namespace AutoRouter;

use Exception;

class Router
{

    private $rootPath ;
    private $defaultController;
    private $defaultMethod ;
    private $namespace ;
    private $controllerSuffix = "Controller";
    private $methodSuffix = "Action";

    private static  $controllerClassName;

    private static $methodClassName;


    /**
     * @var IRouteValidator
     */
    private $validator;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public static function getControllerClassName()
    {
        return self::$controllerClassName;
    }

    /**
     * @return mixed
     */
    public static function getMethodClassName()
    {
        return self::$methodClassName;
    }



    /**
     * @param IRouteValidator $validator
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
    }



    /**
     * @return mixed
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * @return mixed
     */
    public function getDefaultController()
    {
        return $this->defaultController;
    }

    /**
     * @return mixed
     */
    public function getDefaultMethod()
    {
        return $this->defaultMethod;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getControllerSuffix()
    {
        return $this->controllerSuffix;
    }

    /**
     * @return string
     */
    public function getMethodSuffix()
    {
        return $this->methodSuffix;

    }

    /**
     * @param string $rootPath
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;

        return $this;
    }

    /**
     * @param string $defaultController
     */
    public function setDefaultController($defaultController)
    {
        $this->defaultController = $defaultController;

        return $this;
    }

    /**
     * @param string $defaultMethod
     */
    public function setDefaultMethod($defaultMethod)
    {
        $this->defaultMethod = $defaultMethod;

        return $this;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $controllerSuffix
     */
    public function setControllerSuffix($controllerSuffix)
    {
        $this->controllerSuffix = $controllerSuffix;

        return $this;
    }

    /**
     * @param string $methodSuffix
     */
    public function setMethodSuffix($methodSuffix)
    {
        $this->methodSuffix = $methodSuffix;

        return $this;
    }




    public function exec(){
        $status = false;
        $class_path = ltrim($_SERVER['REQUEST_URI'], $this->rootPath);
        $class_path = explode("?", rtrim($class_path,"/"));

        $class_path = $class_path[0];



        $dirs = explode("/", $class_path);
        $total_dirs = count($dirs);


        $controller = $this->defaultController;
        $method = $this->defaultMethod;
        $path = '';

        if($total_dirs == 1 && trim($dirs[0]) != ""){
            $controller = $dirs[0];
        }else if($total_dirs >= 2){
            $controller = $dirs[$total_dirs - 2];
            $method = $dirs[$total_dirs - 1];

            if($total_dirs >= 3){
                $path = implode("\\", array_slice($dirs, 0, $total_dirs - 2)) . "\\";
            }

        }
        $rawMethodName = $method;



        $class_name = $this->namespace . $path . $controller . $this->controllerSuffix;
        $method .= $this->methodSuffix;

        if(class_exists($class_name)) {
            try {

                $instance = new $class_name();
                self::$controllerClassName = $class_name;
                if (method_exists($instance, $method) && ($this->validator == null || $this->validator->validate($class_name,$method)) ) {
                    self::$methodClassName = $method;
                    $instance->$method();
                    $status = true;
                } else {
                    $method = $this->defaultMethod . $this->methodSuffix;

                    if (method_exists($instance, $method)) {
                        self::$methodClassName = $method;
                        $instance->$method($rawMethodName);
                        $status = true;
                    }
                }
            } catch (Exception $e) {
                //var_dump($e);
            }
        }

        return $status;
    }
}