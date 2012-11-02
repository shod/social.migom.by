<?php

/**
 * @ignore
 */
class ERestRender extends ERestComponent {

    private $status = self::STATUS_OK;
    private $content_type = self::TYPE_JSON;
    private $version = '1.0';

    public function init() {

    }

    public function setStatus($status) {
        if (!$this->getStatusCodeMessage($status)) {
            new ApiException(Yii::t('Api', 'Bad status'));
        }
        $this->status = $status;
        return $this;
    }

    public function setContentType($content_type) {
        $this->content_type = $content_type;
        return $this;
    }

    /**
     * Check validation content type
     * @param string $type
     * @return boolean
     */
    public function isValidType($type) {
        if ($type == self::TYPE_JSON || $type == self::TYPE_XML) {
            return true;
        }
        return false;
    }

    private function getStatusCodeMessage($status) {
        $codes = Array(
            self::STATUS_OK => Yii::t('Api', 'OK'),
            self::STATUS_BAD_REQUEST => Yii::t('Api', 'Bad Request'),
            self::STATUS_UNAUTHORIZED => Yii::t('Api', 'Unauthorized'),
            self::STATUS_PAYMENT_REQUIRED => Yii::t('Api', 'Payment Required'),
            self::STATUS_FORBIDDEN => Yii::t('Api', 'Forbidden'),
            self::STATUS_NOT_FOUND => Yii::t('Api', 'Not Found'),
            self::STATUS_INTERNAL_SERVER_ERROR => Yii::t('Api', 'Internal Server Error'),
            self::STATUS_NOT_IMPLEMENTED => Yii::t('Api', 'Not Implemented'),
        );
        return (isset($codes[$status])) ? $codes[$status] : false;
    }

    /**
     * Send api response
     * @param array $content
     */
    public function sendResponse($content = array()) {
        header('HTTP/1.1 200 ' . $this->getStatusCodeMessage($this->status));
        // pages with body are easy
        if ($content == array()) {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($this->status) {
                case self::STATUS_UNAUTHORIZED:
                    $message = Yii::t('Api', 'You must be authorized to view this page.');
                    break;
                case self::STATUS_NOT_FOUND:
                    $message = Yii::t('Api', 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.');
                    break;
                case self::STATUS_INTERNAL_SERVER_ERROR:
                    $message = Yii::t('Api', 'The server encountered an error processing your request.');
                    break;
                case self::STATUS_NOT_IMPLEMENTED:
                    $message = Yii::t('Api', 'The requested method is not implemented.');
                    break;
            }

            $content = array(
                "message" => $message,
                ERestComponent::CONTENT_SUCCESS => false,
            );
        }else{
            if(!isset($content[ERestComponent::CONTENT_SUCCESS])){
                $content[ERestComponent::CONTENT_SUCCESS] = true;
            }
        }
        $body = array(
            'method' => $_SERVER['REQUEST_METHOD'],
            'status' => $this->getStatusCodeMessage($this->status),
            'code' => $this->status,
            'content' => $content,
            'format' => $this->content_type,
            'timestamp' => time(),
            'version' => $this->version,
        );
        $method = 'send' . $this->content_type;

        $this->$method($body);
        Yii::app()->end();
    }

    /**
     * Generate json responce
     * @param array $body
     */
    private function sendJson($body) {
        header('Content-type: application/json');
        echo CJSON::encode($body);
    }

    /**
     * Generate xml responce
     * @param array $body
     */
    private function sendXml($body) {
        header('Content-type: text/xml');
        $xml = new SimpleXMLElement("<responce></responce>");
        foreach ($body as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $xml->addAttribute($key, $value);
        }
        $contentXml = $xml->addChild('content');
        foreach ($body['content'] as $key => $value) {
            $contentXml->$key = $value;
        }
        echo $xml->asXML();
    }

}
