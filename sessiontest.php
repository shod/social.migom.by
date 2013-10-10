<?php
session_name("PHPSESSID");
session_set_cookie_params('2592000','/','.migom.by');
session_start();

print_r(session_id());