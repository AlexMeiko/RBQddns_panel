<?php
function check_ip($ip) {
    if (empty($ip)) return '[Error]ip参数为空';
    $ip = explode('.', $ip);
    if (count($ip) != 4) return '[Error]IP不合法';
    foreach ( $ip as $value ) {
        if ( is_numeric($value) == false || $value > 255 || $value < 0 ) return '[Error]IP不合法';
    }
    return 0;
}