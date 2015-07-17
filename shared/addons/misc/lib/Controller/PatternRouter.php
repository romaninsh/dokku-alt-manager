<?php
namespace misc;
/*
 *
 * This controller allows you to have nice url rewrites without using web 
 * server. Usage:
 *
 * in Frontend:
 *
 * $r = $this->add("Controller_PatternRouter")->addRule("(news\/.*)", "news_item", array("u"))->route();
 *
 * if REQUEST_URI is "/news/some-name-of-your-news/", then router would:
 * 1) set $this->api->page to "news_item"
 * 2) set $_GET["u"] to "news/some-name-of-your-news/"
 * uri.
 *
 * enjoy. j. 2011
 *
 * */
class Controller_PatternRouter extends \AbstractController {
    protected $rules;
    function addRule($regex, $target=null, $params=null){
        $this->rules[] = array($regex, $target, $params);
        return $this;
    }
    function setModel($model){
        if (!is_object($model)){
            $model = $this->add("Model_" . $model);
        }
        $rules = $model->getRows();
        foreach ($rules as $rule){
            $this->addRule($rule["rule"], $rule["target"], explode(",", $rule["params"]));  
        }
        return $this;
    }
    function route(){
        $r=$_SERVER["REQUEST_URI"];
        foreach ($this->rules as $rule){
            if (preg_match("/" . $rule[0] . "/", $r, $t)){
                $this->api->page = $rule[1];
                if ($rule[2]){
                    foreach ($rule[2] as $k => $v){
                        $_GET[$v] = $t[$k+1];
                    }
                }
                break;
            }
        }
    }
}
