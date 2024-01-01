<?php

namespace App\Http\Controllers;

use App\Models\links;
use App\Models\website;
use App\Models\referrer;
use App\Models\click;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use DB;

class LinksController extends Controller
{
    private function logVisitor() {
        $string='';
    
        if(isset($_SERVER['HTTP_USER_AGENT']))
            $string.=filter_input(INPUT_SERVER,'HTTP_USER_AGENT',FILTER_SANITIZE_SPECIAL_CHARS);

        if(isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM']))
            $string.=filter_input(INPUT_SERVER,'HTTP_SEC_CH_UA_PLATFORM',FILTER_SANITIZE_SPECIAL_CHARS);
        
        if(isset($_SERVER['HTTP_ACCEPT']))
            $string.=filter_input(INPUT_SERVER,'HTTP_ACCEPT',FILTER_SANITIZE_SPECIAL_CHARS);
    
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            $string.=filter_input(INPUT_SERVER,'HTTP_ACCEPT_LANGUAGE',FILTER_SANITIZE_SPECIAL_CHARS);
        
        if(isset($_SERVER['HTTP_ACCEPT_ENCODING']))
            $string.=filter_input(INPUT_SERVER,'HTTP_ACCEPT_ENCODING',FILTER_SANITIZE_SPECIAL_CHARS);
    
        $hash=hash('xxh3',$string);

        $affected=DB::table('visitors')->where('hash',$hash)->where('visit_date',Carbon::today())->increment('counter');

        if($affected==0) {
            DB::table('visitors')->insert([
                'hash'=>$hash,
                'visit_date'=>Carbon::today(),
                'counter'=>1
            ]);
        }
    }    

    private function logReferer() {
        if (isset($_SERVER['HTTP_REFERER']) && filter_var($_SERVER['HTTP_REFERER'],FILTER_VALIDATE_URL)) {
            $referrer=parse_url(filter_input(INPUT_SERVER,'HTTP_REFERER',FILTER_SANITIZE_SPECIAL_CHARS));

            if($referrer){
                $unnecessary_entries=array();

                if(!in_array($referrer['host'],$unnecessary_entries)) {
                    $referrer_host_id=DB::table('websites')->where('host',$referrer['host'])->value('id');

                    if(empty($referrer_host_id)) {
                        $model_result=website::create(['host'=>$referrer['host']]);
                        $referrer_host_id=$model_result->id;
                    }

                    $referrer_path=trim($referrer['path'],'/');
                    if(isset($referrer['query'])) {
                        $referrer_query=$referrer['query'];
                    }
                    else {
                        $referrer_query='';
                    }
                    
                    $model_result=referrer::where('website_id',$referrer_host_id)
                                            ->where('visit_date',Carbon::today())
                                            ->where('path',$referrer_path)
                                            ->where('query',$referrer_query)
                                            ->increment('counter');
                    
                    if($model_result==0) {
                        referrer::create([
                            'website_id'=>$referrer_host_id,
                            'path'=>$referrer_path,
                            'query'=>$referrer_query,
                            'visit_date'=>Carbon::today(),
                            'counter'=>1
                        ]);
                    }
                } 
            }
        }
    }

    public function index()
    {
        self::logVisitor();
        self::logReferer();

        return view('welcome',['links'=>Links::select('id','name','link_type')->where('is_active',1)->orderBy('sort_order')->get(),
                               'link_types'=>DB::table('link_types')->select('id','name')->where('is_active',1)->orderBy('sort_order')->get()]);
    }

    public function redirect($id) {
        $id_clean=filter_var($id,FILTER_VALIDATE_INT);
        if (!$id_clean) abort(404);

        $url=Links::where('id',(int) $id_clean)->value('url');

        if(empty($url)) abort(404);

        $model_result=click::where('link_id',(int)$id_clean)->
                                where('click_date',Carbon::today())->
                                increment('counter');

        if($model_result==0) {
            click::create([
                'link_id'=>(int) $id_clean,
                'click_date'=>Carbon::today()
            ]);
        }

        //echo $url;
        return redirect('https://'.$url);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(links $links)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(links $links)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, links $links)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(links $links)
    {
        //
    }
}
