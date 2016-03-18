<?php

/**
 * Dokku-alt-manager API is authenticating using token
 */
class Api extends App_REST
{
    //public $api_log=[];
    function authenticate()
    {

        // Verify Token
        $this->config = $this->add('Model_Config')->load(1);

        if(!$this->config['is_restapi_enabled']){
            throw $this->exception('RestAPI is disabled. Enable in UI.', 403);
        }

        if ($_SERVER['PHP_AUTH_USER']) {
            if (strtolower($_SERVER['PHP_AUTH_USER']) == 'token') {


                if($_SERVER['PHP_AUTH_PW']!= $this->config['rest_api_shared_secret']){
                    //$this->api_log['auth_data']='incorrect token: '.$_SERVER['PHP_AUTH_PW'];
                    throw new $this->exception('Authentication Failure', 403);
                } // else yey
            }else throw new $this->exception('Specify Authentication Token', 403);
        }elseif($_GET['token'] && $_GET['token'] == $this->config['rest_api_shared_secret']){
            // yey

        }else throw $this->exception('Specify Authentication Token', 403);


        return null;
    }


    function init()
    {
        parent::init();

        // establish routes
        $this->dbConnect();
        //$this->api_log=$this->add('Model_ApiLog')
            //->set('status','init')
            //->save();

        /*
        $this->add('Controller_PatternRouter')
            ->link('1/plugins', array('id', 'method', 'arg1', 'arg2'))
            ->route();
            */

    }
    /*
    function logRequest($method, $args) {
        $this->api_log['interface']=$this->page;
        $this->api_log['status']='request';
        $this->api_log['method']=$method;
        $this->api_log['params']=$args;

        $this->api_log['params']=$args;

        $this->api_log->saveLater();
    }
    function logSuccess() {
        $this->api_log['status']='complete';
    }
    public function encodeOutput($data){
        if($this->api_log && $this->api_log->loaded()){
            $this->api_log['response']=json_encode($data);
            $this->api_log->save();
        }
        return parent::encodeOutput($data);
    }
    function outputDebug($caller,$msg,$shift=0){

        $frame=$this->logger->findFrame('debug');
        $line = $this->logger->htmlLine("$msg",$frame,'debug');

        $this->api_log->ref('ApiLogDebug')->set('text',$line)->saveAndUnload();
        return parent::outputDebug($caller,$msg,$shift);
    }

    function caughtException($e){

        $this->api_log['status']='exception';


        $this->api_error=$this->add('Model_ApiError');

        $this->api_error['code']=$e->getCode();
        $this->api_error['uri']=$this->page;

        $this->api_error['post']=$_POST;
        $this->api_error['get']=$_GET;

        $this->api_error['api_log_id']=$this->api_log->id;

        $html = method_exists($e,'getHTML')?$e->getHTML():$e->getMessage();


        $this->api_error['error_message']=$e->getMessage();

        $this->api_error['error']=$html;

        $v=$this->add('View')->add('View',null,null,['html']);
        $l=$v->add('Layout_Error');
        $v->template->trySet('css','http://css.agiletoolkit.org/css/theme.css');
        $l->template->setHTML('Content',$html);
        $v->template->trySet('page_title','Error: '.$e->getMessage());

        try {
            $this->api_error->save();
        }catch (Exception $e){
            parent::caughtException($e);
        }


        return true;
    }
    */
}
