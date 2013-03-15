<?php
class Render extends QWidget {

    public $data;
	public $viewFile;
	private $_html = '';
	
	public function beforeHtml()
	{
		return $this->_html;
	}
	
	public function html()
    {
        if(!$this->viewFile){
            return false;
        }
        $this->beforeHtml();
		$this->_html .= parent::render($this->viewFile, $this->getData(), true);
        $this->afterHtml();
		return $this->_html;
    }
	
}