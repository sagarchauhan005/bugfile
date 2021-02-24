<?php
namespace sagar\BugFile;

class BugFile
{
    /*updated*/
    private $env, $end_point, $key, $severity, $message, $user, $withData, $logger, $logData, $source, $config=null;
    const LOG_INFO=0;
    const LOG_DEBUG=1;
    const LOG_NOTICE=2;
    const LOG_WARNING=3;
    const LOG_ERROR=4;
    const LOG_CRITICAL=5;
    const LOG_ALERT=6;
    const LOG_EMERGENCY=7;
    const DEFAULT_MESSAGE = "General Log";
    const DEFAULT_LOGGER = "Admin";
    const DEFAULT_SOURCE = "Laravel Exception Handler";
    const DEFAULT_USER = '-';
    const DEFAULT_DATA = '-';
    const IS_CORE_PHP = false;

    public function __construct($config=null)
    {
        $this->config = ($config===null) ? self::getConfig() : $config;
        $this->env = $this->config['APP_ENV'];
        $this->end_point = $this->config['BUGFILE_END_POINT'];
        $this->key = self::getKey($this->env);
        $this->severity = BugFile::LOG_INFO;
        $this->message = BugFile::DEFAULT_MESSAGE;
        $this->user = BugFile::DEFAULT_USER;
        $this->withData = BugFile::DEFAULT_DATA;
        $this->source = BugFile::DEFAULT_SOURCE;
        $this->logger = BugFile::DEFAULT_LOGGER;
        $this->logData = '-';
    }

    /**
     * Returns the config for the different environment and connection
     * @return array
     */
    private static function getConfig(): array
    {
        return [
            'APP_ENV'=>env('APP_ENV'),
            'BUGFILE_END_POINT'=>env('BUGFILE_END_POINT'),
            'BUGFILE_KEY_DEV'=>env('BUGFILE_KEY_DEV'),
            'BUGFILE_KEY_STAGING'=>env('BUGFILE_KEY_STAGING'),
            'BUGFILE_KEY_LIVE'=>env('BUGFILE_KEY_LIVE')
        ];
    }

    /**
     * Returns the payload data as arrau
     * @return array
     */
    private function getData(): array
    {
        return [
            'severity'=>$this->severity,
            'message'=>$this->message,
            'user'=>$this->user,
            'properties'=>$this->withData,
            'logger'=>$this->logger,
            'log'=>$this->logData,
            'source'=>$this->source
        ];
    }

    /** Defines the serverity of the error
     * @param $level
     */
    public function setSeverity($level){
        $this->severity = $level;
    }

    /**
     * Defines the message or comment for each log
     * @param $message
     */
    public function setMessage($message){
        $this->message = $message;
    }

    /**
     * Defines the user who caused the bug
     * @param $user
     */
    public function causedBy($user){
        $this->user = $user;
    }

    /**
     * Defines the source at which the bug was caused
     * @param $source
     */
    public function causedAt($source){
        $this->source = $source;
    }

    /**
     * Defines any custom data that a logger might need
     * @param $data
     */
    public function customData($data){
        $this->withData = $data;
    }

    /**
     * Name of the user who logged the bug
     * @param $name
     */
    public function loggedBy($name){
        $this->logger = $name;
    }

    /**
     * Original log data as arrau
     * @param $e
     */
    public function log($e){
        $this->logData = [
            'message'=>$e->getMessage(),
            'stacktrace'=>$e->getTraceAsString(),
            'file'=>$e->getFile(),
            'line'=>$e->getLine(),
            'code'=>$e->getCode(),
        ];
    }

    /**
     * Performs the curl operation to the tools endpoint
     */
    public function save(){
        $url = $this->end_point;
        $data = self::getData();
        $postdata = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','X-Authorization:'.$this->key));
        $result = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Gets the key for different environment
     * @param $env
     * @throws Exception
     */
    private function getKey($env)
    {
        try{
            if($env==="local"){
                return $this->config['BUGFILE_KEY_DEV'];
            }elseif($env==="staging"){
                return $this->config['BUGFILE_KEY_STAGING'];
            }elseif($env==="production"){
                return $this->config['BUGFILE_KEY_LIVE'];
            }
        }catch (Exception $e){
            throw new Exception('BUG_FILE_ENV value not found');
        }
    }
}
