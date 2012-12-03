<?php
class HttpRequest extends CHttpRequest
{
        const PARAM_TYPE_LIST = 'list';

        public function getParam($Name, $Default = "", $type = null, $list = array())
        {
            $r = $Default;
            if (isset($_GET [$Name])) {
                $r = $_GET [$Name];
            } else if (isset($_POST [$Name])){
                $r = $_POST [$Name];
            }
            if ($type == self::PARAM_TYPE_LIST){
                $r = self::checkFromList($r, $Default, $list);
            }else{
                $r = self::toTypeConvert($r, $type, $list);
            }

            return $r;
        }

        private function checkFromList($value, $default, $list) {
            if(in_array($value, $list)){
                return $value;
            }
            return $default;
        }

        private function toTypeConvert($value = null, $type = null) {
            switch ($type) {
                case 'int':
                    $value = intval($value);
                    break;
                case 'str':
                    $value = strval($value);
                    break;
                case 'double':
                    $value = doubleval($value);
                    break;
                case 'float':
                    $value = floatval( str_replace( ',', '.',$value));
                    break;
                case 'checkbox':
                    $value = ($value == 'on')?1:0;
                    break;
                default:
                    break;
            }
            return $value;
        }
}
?>