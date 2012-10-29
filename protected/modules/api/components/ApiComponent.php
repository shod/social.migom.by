<?php
/**
 * @package component
 */
class ApiComponent extends CComponent {

    const TYPE_JSON = 'json';
    const TYPE_XML = 'xml';
    const STATUS_OK = 200;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_PAYMENT_REQUIRED = 402;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;

    const CONTENT_MESSAGE = 'message';
    const CONTENT_SUID = 'suid';
    const CONTENT_PUID = 'puid';
    const CONTENT_ID = 'id';
    const CONTENT_SUCCESS = 'success';
    const CONTENT_COUNT = 'count';
    const CONTENT_ITEMS = 'items';
    const CONTENT_ITEM = 'item';
    const CONTENT_RESPONCE = 'result';

}
