<?php

    // main ================================================= //
    function writeLog(string $message) {

        openlog("myapp", LOG_PID | LOG_PERROR, LOG_LOCAL0);

        syslog(LOG_WARNING, $message);

    }

?>