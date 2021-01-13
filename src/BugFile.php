<?php
namespace sagar\BugFile;
use Ixudra\Curl\Facades\Curl;

class BugFile
{
    private $env, $end_point, $key, $severity, $message, $user, $withData, $logger, $logData, $source;
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

    public function __construct()
    {
        $this->env = env('APP_ENV');
        $this->end_point = env('BUGFILE_END_POINT');
        $this->key = self::getKey($this->env);
        $this->severity = BugFile::LOG_INFO;
        $this->message = BugFile::DEFAULT_MESSAGE;
        $this->user = BugFile::DEFAULT_USER;
        $this->withData = BugFile::DEFAULT_DATA;
        $this->source = BugFile::DEFAULT_SOURCE;
        $this->logger = BugFile::DEFAULT_LOGGER;
        $this->logData = '-';
    }

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

    public function setSeverity($level){
        $this->severity = $level;
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function causedBy($user){
        $this->user = $user;
    }

    public function causedAt($source){
        $this->source = $source;
    }

    public function customData($data){
        $this->withData = $data;
    }

    public function loggedBy($name){
        $this->logger = $name;
    }

    public function log($e){
        $this->logData = [
            'message'=>$e->getMessage(),
            'stacktrace'=>$e->getTraceAsString(),
            'file'=>$e->getFile(),
            'line'=>$e->getLine(),
            'code'=>$e->getCode(),
        ];
    }

    public function save(){
        $data =  self::getData();
        $response = Curl::to($this->end_point)
            ->withHeaders([
                'X-Authorization:'.$this->key
            ])
            ->withData($data)
            ->asJson()
            ->returnResponseArray()
            ->get();
    }

    /**
     * @param $env
     * @throws Exception
     */
    private static function getKey($env)
    {
        try{
            if($env==="local"){
                return env('BUGFILE_KEY_DEV');
            }elseif($env==="staging"){
                return env('BUGFILE_KEY_STAGING');
            }elseif($env==="production"){
                return env('BUGFILE_KEY_LIVE');
            }
        }catch (Exception $e){
            throw new Exception('BUG_FILE_ENV value not found');
        }
    }
}
