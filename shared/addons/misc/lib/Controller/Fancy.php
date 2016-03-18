<?php
/*
   Selection of utilities for making thinsg fancy


   Due to date functions, this controller requries PHP5.3+
   */
namespace misc;

class Controller_Fancy extends \AbstractController {
    function fancy_datetime($dt,$now='now'){

        if(is_int($dt))$dt='@'.$dt;
        if(is_int($now))$dt='@'.$now;

        $now=new \DateTime($now);
        $dt=new \DateTime($dt);

        $interval=$dt->diff($now);
        $rel=$dt>$now?'':' ago';

        if($interval->format('%a')>30){
            return $dt->format($this->api->getConfig('locale/date','d/m/Y'));
        }

        $d=$interval->format('%a');
        if($d>1)return $d.' days'.$rel;

        // Zero days, show fancy format
        $h=$interval->format('%h')+$d*24;
        if($h>1)return $h.' hours'.$rel;
        if($h)return 'a hour'.$rel;


        $m=$interval->format('%i');
        if($m>1)return $m.' minutes'.$rel;

        return $dt>$now?'about a minute':'just now';
    }
}
