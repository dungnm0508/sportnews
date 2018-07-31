<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;
use Illuminate\Support\Facades\DB;
class CronController extends Controller
{
    public function getData(){
    	include('simple_html_dom.php');
    	$html = file_get_html('http://dantri.com.vn/the-thao.htm');
    	$data = [];
    	foreach($html->find('#listcheckepl div.mt3') as $element) {
    		$el_subnew =$element->find("div.mr1 div.fl", 0)->plaintext;
    		$subnews_explode = explode('&nbsp;&gt;&gt;&nbsp;', $el_subnew);
    		$subnews = substr( $subnews_explode[0],14);
    		$tag_news_info = [
    			'href' => $element->find("a", 0)->href,
    			'title' => $element->find("a", 0)->title,
    			'img_src' =>$element->find("a img", 0)->src,
    			'subnews'=>$subnews,
    		];
    		$data[] = $tag_news_info;
    	}
        $news = News::all();
        $dataDB = [];
        foreach ($news as $value) {
            $arrayItem = json_decode($value,true);
            unset($arrayItem['id'],$arrayItem['created_at'],$arrayItem['source'],$arrayItem['updated_at']);
            $dataDB[] = $arrayItem;
        }
        $dataDiff = $this->array_diff_assoc_recursive($dataDB,$data);
        // var_dump($dataDiff);
    	$this->saveDataTagNew($dataDiff);
    }

    public function saveDataTagNew($data){
    	foreach ($data as $value) {
    		$new = new News;
    		$new->href = $value['href'];
    		$new->title = $value['title'];
    		$new->img_src = $value['img_src'];
    		$new->subnews = $value['subnews'];
    		$new->source = 1;
    		$new->save();
    	}
    }
    public function getDataTest(){
    	$news= News::all()->reverse();
    	return view('test',compact('news'));
    }
    public function array_diff_assoc_recursive($array1, $array2)
    {
        $times = array();
        foreach ($array1 as $k=>$x) {
            $href[$x['href']] = $x;
        }
        $_out = array();
        foreach ($array2 as $y) {
            if (!array_key_exists($y['href'], $href)) {
                array_push($_out, $y);
            } 
        }
        return $_out;
    }
    public function getDashBoard(){
        $news= News::all()->reverse();
        return view('layouts/pages/dashboard',compact('news'));
    }

}
