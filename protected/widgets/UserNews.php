<?php

class UserNews extends CWidget
{

    const NEWS_ON_WALL = 10;

    public $user_id;
    public $news;
    public $offset = 0;

    public function run()
    {
        if ($this->news && is_array($this->news->entities)) {
            krsort($this->news->entities);
            foreach ($this->news->entities as $key => $en) {
                if (in_array($en->filter, $this->news->disable_entities)) {
                    unset($this->news->entities[$key]);
                }
            }
        }
        if ($this->offset) {
            $this->offset = array_pop(explode('_', $this->offset));
        }

        if (!$this->offset) {
            $this->render('userNews', array('news' => $this->news));
        } else {
            $this->render('ajaxAppend', array('news' => $this->news));
        }
    }

}