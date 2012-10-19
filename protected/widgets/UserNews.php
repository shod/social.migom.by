<?php
class UserNews extends CWidget {
    
    public $user_id;
    public $news;

    public function run()
    {
        if($this->news && is_array($this->news->entities)){
            krsort($this->news->entities);
            foreach ($this->news->entities as $key => $en){
                if(in_array($en->filter, $this->news->disable_entities)){
                    unset($this->news->entities[$key]);
                }
            }
        }
        $this->render('userNews', array('news' => $this->news));
    }
}