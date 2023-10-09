<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function index(){
        return view('home');
    }

    public function search(Request $request){
        // dd($request->all());
        // Validate the request
        $data = $request->validate([
            'keyword' => 'required|string|max:255'
        ]);

        $results = Post::where('title', 'like', '%' . $request->keyword . '%')
              ->orWhere('body', 'like', '%' . $request->keyword . '%')
              ->get();
              if (!empty($results) && count($results) > 0){
                $result = true;
              } else{
                $result = false;
              }   
        // search history data save
        $history = new SearchHistory();
        $history->user_id = auth()->id();
        $history->keyword = $request->keyword;
        $history->result = $result;
        $history->ip = $request->ip();
        $history->searched_at = Carbon::now();
        $history->save();
        return view('_search_result',compact('results'));
        
    }

    public function search_history(Request $request){
      $keywords = SearchHistory::select('keyword', DB::raw('count(keyword) as count'))
      ->groupBy('keyword')
      ->pluck('count', 'keyword')
      ->toArray();

      // Get all users
      $users = User::select('id','name')->get();
      // Get all search histories (or paginate them if you expect a large number)
      if($request->ajax()){
        $query = SearchHistory::with('user');

        // Filtering based on keyword
        if (!empty($request->input('filters.keyword'))) {
            $query->whereIn('keyword', $request->input('filters.keyword'));
        }
    
        // Filtering based on user
        if (!empty($request->input('filters.user'))) {
            $query->whereIn('user_id', $request->input('filters.user'));
        }
   
        if (!empty($timeFilters)) {
            foreach ($timeFilters as $filter) {
                switch ($filter) {
                    case 'yesterday':
                        $query->whereDate('searched_at', Carbon::yesterday());
                        break;
                    case 'last-week':
                        $query->whereBetween('searched_at', [Carbon::now()->subWeek(), Carbon::now()]);
                        break;
                    case 'last-month':
                        $query->whereBetween('searched_at', [Carbon::now()->subMonth(), Carbon::now()]);
                        break;
                }
            }
        }
        
    
        if ($request->input('startDate') !=null && $request->input('endDate') != null) {
         
            $query->whereBetween('searched_at', [$request->input('startDate'), $request->input('endDate')]);
        }
        $searchHistories = $query->get();
        // dd($searchHistories);
        // $searchHistories = $query->get();
       
      }else{
        $searchHistories = SearchHistory::with('user')->orderBy('searched_at', 'desc')->get();
      }
      
      $view = $request->ajax() ? "_search_history" : "search_history";
      return view($view, [
        'keywords' => $keywords,
        'users' => $users,
        'searchHistories' => $searchHistories
      ]);
    }
}
