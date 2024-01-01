<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\referrer;

use DB;

class AnalyticsController extends Controller
{
    public function visitors(Request $request) {
        $date_from=$request->query('from');
        $date_to=$request->query('to');

        $db_return=DB::select("SELECT SUM(COALESCE(visitors.counter,0)) as total_loads,COUNT(visitors.hash) as unique_hashes,date(generator.days) FROM 
                                (SELECT * FROM generate_series(:date_from,:date_to,'1 day'::interval) as days) generator
                                LEFT JOIN visitors ON visitors.visit_date=generator.days
                                GROUP BY generator.days
                                ORDER BY generator.days ASC;",
                                [
                                    'date_from'=>filter_var($date_from,FILTER_SANITIZE_NUMBER_INT),
                                    'date_to'=>filter_var($date_to,FILTER_SANITIZE_NUMBER_INT)
                                ]);
        
        return response()->json($db_return,200);
    }

    public function referrers_paths(Request $request) {
        $id_clean=filter_var($request->query('id'),FILTER_VALIDATE_INT);
        if (!$id_clean) abort(500);

        $db_return=referrer::select('id AS key','path','query','counter','visit_date')
                            ->where('website_id',(int)$id_clean)
                            ->orderBy('counter','desc')
                            ->get();

        return response()->json($db_return,200);
    }

    public function referrers(Request $request) {
        $date_from=$request->query('from');
        $date_to=$request->query('to');

        $db_return=DB::select("SELECT websites.host,websites.id,SUM(referrers.counter)
                                FROM referrers
                                JOIN websites ON referrers.website_id=websites.id
                                WHERE referrers.website_id NOT IN (4,5)
                                AND referrers.visit_date BETWEEN :date_from AND :date_to
                                GROUP BY websites.host,websites.id
                                ORDER BY SUM(referrers.counter) DESC;",
                                [
                                    'date_from'=>filter_var($date_from,FILTER_SANITIZE_NUMBER_INT),
                                    'date_to'=>filter_var($date_to,FILTER_SANITIZE_NUMBER_INT)
                                ]);

        return response()->json($db_return,200);
    }

    public function clicks(Request $request) {
        $date_from=$request->query('from');
        $date_to=$request->query('to');

        $db_return=DB::select("SELECT links.name,links.id,SUM(clicks.counter)
                                FROM clicks
                                JOIN links ON links.id=clicks.link_id
                                WHERE clicks.click_date BETWEEN :date_from AND :date_to
                                GROUP BY links.name,links.id
                                ORDER BY SUM(clicks.counter) DESC;",
                                [
                                    'date_from'=>filter_var($date_from,FILTER_SANITIZE_NUMBER_INT),
                                    'date_to'=>filter_var($date_to,FILTER_SANITIZE_NUMBER_INT)
                                ]);

        return response()->json($db_return,200);
    }

    public function website_info(Request $request) {

    }
}
