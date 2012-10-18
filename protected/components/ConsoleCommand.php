<?php

class ConsoleCommand extends CConsoleCommand {

    public $actions = 1;
    public $params = array();
    public $fromQueue = true;
    
    public function init() {
        // тут мы проверяем уровень загрузки сервера!!!
        // 
        // проверяем небыл ли запущен этот воркер рание
        $file = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands' . DIRECTORY_SEPARATOR . 'lock' . DIRECTORY_SEPARATOR . $this->name;
        $fo = fopen($file, 'w');
        if(!flock($fo, LOCK_EX | LOCK_NB)){
            die('process in work!');
        }
        $this->setTimeLimit();
    }

    public function run($args) {
        // проверяем есть ли нужный нам метод
        list($action, $options, $args) = $this->resolveRequest($args);
        $methodName = 'action' . $action;
        if (!preg_match('/^\w+$/', $action) || !method_exists($this, $methodName))
            $this->usageError("Unknown action: " . $action);
        
        if(!empty($options))
        {
                $class=new ReflectionClass(get_class($this));
                foreach($options as $name=>$value)
                {
                        if($class->hasProperty($name))
                        {
                                $property=$class->getProperty($name);
                                if($property->isPublic() && !$property->isStatic())
                                {
                                        $this->$name=$value;
                                        unset($options[$name]);
                                }
                        }
                }
        }

        // set не обязательных параметров
        if (!empty($options)) {
            foreach ($options as $name => $value) {
                $this->params[$name] = $value;
                unset($options[$name]);
            }
        }
        
        if($this->fromQueue){
            for ($i = 0; $i < $this->actions; $i++) {
                // get job worker`s from queue
                $criterea = new EMongoCriteria();
                $criterea->addCond('what', '==', $this->name . ' ' . $action);
                $criterea->sort('priority', EMongoCriteria::SORT_DESC);
                $criterea->limit(1);

                $aJob = Queue::model()->findAll($criterea);
                if(!count($aJob)){
                    return 0;
                }
                $job = array_pop($aJob);
                $this->setJobInWork($job); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

                $this->params = array_merge($job->param, $this->params);
                $params = array_merge(get_object_vars($job), $this->params);
                $method = new ReflectionMethod($this, $methodName);
                $params = $this->getParams($method, $params);

                $exitCode = 0;
                if ($this->beforeAction($action, $params)) {
                    $exitCode = $method->invokeArgs($this, $params);
                    $exitCode = $this->afterAction($action, $params, $exitCode);
                }
                $this->deleteJob($job, $exitCode); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            }
        } else {
            $method = new ReflectionMethod($this, $methodName);
            $params = $this->getParams($method, $this->params);

            $exitCode = 0;
            if ($this->beforeAction($action, $params)) {
                $exitCode = $method->invokeArgs($this, $params);
                $exitCode = $this->afterAction($action, $params, $exitCode);
            }
        }
        
        return $exitCode;
    }
    
    public function getParams($method, $options){
        
        $params = array();
        // named and unnamed options
        foreach ($method->getParameters() as $i => $param) {
            $name = $param->getName();
            if (isset($options[$name])) {
                if ($param->isArray())
                    $params[] = is_array($options[$name]) ? $options[$name] : array($options[$name]);
                else if (!is_array($options[$name]))
                    $params[] = $options[$name];
                else
                    $this->usageError("Option --$name requires a scalar. Array is given.");
            }
            else if ($name === 'args')
                $params[] = $args;
            else if ($param->isDefaultValueAvailable())
                $params[] = $param->getDefaultValue();
            else
                $this->usageError("Missing required option --$name.");
            unset($options[$name]);
        }
        
        return $params;
    }
    
    protected function setJobInWork($job){
        
    }
    
    protected function deleteJob($job, $exitCode){
        if($exitCode != true){
            $job->priority -= 10;
            $job->save();
        } else{
            $job->delete();
        }
    }
    
    public function setTimeLimit(){
        $consoleParams = Yii::app()->params[$this->name];
        if(isset($consoleParams['time_limit'])){
            set_time_limit($consoleParams['time_limit']);
        }
    }
}