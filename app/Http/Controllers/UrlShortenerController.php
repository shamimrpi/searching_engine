<?php 
namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UrlShortenerController extends Controller
{
    /**
     * Show the form for creating a new shortened URL.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        check_user_access('url_create');
        return view('urls.create');
    }

    /**
     * Store the shortened URL in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['long_url' => 'required|url']);

        try {
            do {
                $shortCode = Str::random(6);
            } while (Url::where('short_code', $shortCode)->exists());

            $url = Url::create([
                'long_url' => $request->long_url,
                'short_code' => $shortCode, 
                'user_id' => auth()->id()
            ]);
            session()->flash('success', 'URL has been shortened successfully!');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('danger', $e->getMessage());
        }
       
    }

    public function edit(Url $url){
        check_user_access('url_edit');
        return view('urls.edit',compact('url'));
    }

    public function update(Request $request,Url $url)
    {
        $request->validate(['long_url' => 'required|url']);

        try {
        
        $url->update([
            'long_url' => $request->long_url,
            'user_id' => auth()->id()
        ]);
        session()->flash('success', 'URL has been updated successfully!');
        return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('danger', $e->getMessage());
            
        }
       
    }

    public function show(Url $url)
    {
        check_user_access('url_show');
        return view('urls.show', compact('url'));
    }

    /**
     * Redirect to the original long URL.
     *
     * @param  string  $shortCode
     * @return \Illuminate\Http\Response
     */
    public function redirect($shortCode)
    {
        $url = Url::where('short_code', $shortCode)->firstOrFail();
        $url->increment('clicks');

        return redirect($url->long_url);
    }

    /**
     * Show user's shortened URLs and statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {   
       
        $urls = Url::select('id','long_url','short_code','clicks')->get();
        
        return view('urls.index', compact('urls'));
    }

    
    public function destroy(Url $url){
        check_user_access('url_delete');
        try {
            $url->delete();
            session()->flash('success', 'URL has been deleted successfully!');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
