<?php

class LikesCommand extends ConsoleCommand {
    
    public function actionUpdateStatistics() {
        
        foreach ($this->params as $server => $model) {
            if(is_array($model)){
                foreach($model as $modelName){
                    $this->workUpdateStatistics($server, $modelName);
                }
            } else {
                $this->workUpdateStatistics($server, $model);
            }
        }
        print_r($this->params);
        sleep(10);
    }
    
    protected function workUpdateStatistics($server, $model){
        $model = Likes::model($server.'_'.$model);
        var_dump($model);
    }
}