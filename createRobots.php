<?php

/**
 * @description  一键生成网站bobots.txt 文件
 * 运行接受2种方式传参url
 * php <programe.php> [url]
 * php <programe.php> --u=[url]
 * @author 小孩别跑
 * @email  wuchuheng@163.com
 * @date    2018-09-18
 */

class run
{

    public $argv; //参数


    /**
     * @info 初始化 参数初始化
     *
     */
    public function __construct($params)
    {
       //开启错误
        ini_set('display_errors','On');
        $this->argv = $params['argv'];
        $this->init();
    }


    /**
     * @info 系统参数和输入参数检验
     * @return json
     */
    public  function init()
    {
        if(PHP_OS !== 'Linux') exit('please, runing the program on Linux'."\n");
       //检验系统函数exec()和shell_exec()是否开放
        $report  =  strstr(ini_get('disable_functions'),'exec') ? "exec() ," : '';
        $report .=  strstr(ini_get('disable_functions'),'shell_exec') ? "shell_exec() " : '';
        if($report) exit(" allow  funciton of {$report} in the disable_functions line of the php.ini");
        //检验curl和pcntl扩展
        $report  = function(){
            $report  =  extension_loaded('curl') ? '' : "\e[32m curl extention  \e[0m \t \e[31m No \e[0m \n";
            $report .=  extension_loaded('pcntl') ? '' : "\e[32m pcntl extention \e[0m \t \e[31m  No \e[0m \n";
            return $report;
        };
        if($report()) exit($report());
        //用户输入参数检验
        $webSite = function($prompt='pleale enter your target website!'){
            if(!isset($this->argv[1])){
                fwrite(STDOUT,$prompt);
                $rul = fgets(STDOUT);
            }else{
                $url = count(getopt('u::')) > 0 ? getopt('u::')['u'] : $this->argv[1];
            }
            $url = strstr($url,'http://') ? $url : "http://".$url;
            if($this->getopt($url) !== 200 ) $webSite();
            else return $url;
        };

        //url不可访问，重新输入

    }


    /**
     * @info      获取http状态码
     * @param     string        $url    网址
     * @return    numeral
     *
     */
    public function getHttpCode($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); //设置URL
        curl_setopt($curl, CURLOPT_HEADER, 1); //获取Header
        curl_setopt($curl,CURLOPT_NOBODY,true); //Body就不要了吧，我们只是需要Head
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //数据存到成字符串吧，别给我直接输出到屏幕了
        curl_exec($curl); //开始执行啦～
        $httpcode=curl_getinfo($curl,CURLINFO_HTTP_CODE); //我知道HTTPSTAT码哦～
        curl_close($curl); //用完记得关掉他
        return $httpcode;
    }



    /**
     * @info cli面板输出
     *
     */

}

//开始执行
new run(['argv'=>$argv]);
