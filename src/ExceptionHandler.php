<?php

namespace sagar\BugFile;
use sagar\BugFile\BugFile;

/**
 * Handles core PHP's exception and send them to Kartmax's bug tracker.
 * @author Harish Rawat
 */

class ExceptionHandler {

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    //handle exception
    public function handleException()
    {
        set_exception_handler(function($e) {
            $sourceMessage = $e->getCode().' on line number '.$e->getLine().' trace '.$e->getTraceAsString();
            $this->trackBug($e, $sourceMessage, 'critical');
            header('Content-Type: application/json');
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET");
            header('Access-Control-Allow-Headers: "DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,C$');
            header('Access-Control-Allow-Credentials: true');
            header('Content-Type: application/json;charset=utf-8');
            http_response_code(500);
            echo json_encode(['message' => 'Server Error'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        });
    }

    /**
     * Set bug tracker configuration
     */
    public function getBTConfig()
    {
        return [
            'APP_ENV' => $this->config['APP_ENV'],
            'BUGFILE_END_POINT' => $this->config['BUGFILE_END_POINT'],
            'BUGFILE_KEY_DEV' => $this->config['BUGFILE_KEY_DEV'],
            'BUGFILE_KEY_STAGING' => $this->config['BUGFILE_KEY_STAGING'],
            'BUGFILE_KEY_LIVE' => $this->config['BUGFILE_KEY_LIVE'],
        ];
    }

    /**
     * Track bug
     * @param instanceof Exception
     * @param string $source
     * @param string $severity
     */
    public function trackBug($error, $source = 'PIM', $severity = 'info')
    {
        $btObj = new BugFile($this->getBTConfig());
        $btObj->causedAt($source);
        $btObj->setSeverity($this->getSeverityCode($severity));
        $btObj->loggedBy('Admin');
        $btObj->log($error);
        $btObj->save();
    }

    //Get bug tracker status code
    public function getSeverityCode($severity)
    {
        $codes = ['info', 'debug', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];
        if($code = array_search($severity, $codes))
        {
            return $code;
        }
        return 0;
    }
}