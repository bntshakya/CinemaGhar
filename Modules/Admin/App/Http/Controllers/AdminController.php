<?php

// namespace App\Http\Controllers;
namespace Modules\Admin\App\Http\Controllers;

use App\DataTables\RegistersDataTable;
use App\DataTables\RevenueDataTable;
use App\Events\salesSearchEvent;
use App\Jobs\StopBooking;
use App\Models\Location;
use App\Models\movie;
use App\Models\MovieScreeningCost;
use App\Models\Movietime;
use App\Models\register;
use App\Models\Revenue;
use App\Models\RevenueSummary;
use App\Models\ticket;
use App\Models\TicketRate;
use App\Models\TrackMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Features\SupportTesting\Render;
use Symfony\Component\EventDispatcher\EventDispatcher;
use App\Http\Controllers\Controller;


class AdminController extends Controller
{
    //
    public function viewcustomers()
    {
        return view('admin::components.admin.customers');
    }
    public function viewsidebar()
    {
        return view('admin::components.admin.sidebar');
    }
    public function showpanel()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Get the start of the week (Monday)
        $endOfWeek = Carbon::now()->endOfWeek();     // Get the end of the week (Sunday)
        $dates = [];
        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
            $dates[] = $date->toDateString(); // Collect dates in 'YYYY-MM-DD' format
        }
        $todaydate = Carbon::today();
        $moviesales = [];
        foreach ($dates as $date) {
            $movies = ticket::where(DB::raw('DATE(created_at)'), $date)->count();
            array_push($moviesales, $movies);
        }
        $movienames = ticket::pluck('movie_name')->unique()->values();
        // dd($movienames);
        $a = [];
        $b = [];
        foreach ($movienames as $moviename) {
            foreach ($dates as $date) {
                $sales = ticket::where(DB::raw('DATE(created_at)'), $date)->where('movie_name', $moviename)->count();
                array_push($a, $sales);
            }
            array_push($b, $a);
            $a = [];
        }
        $movienames = $movienames->toArray();
        $tickets = ticket::all();
        $locations = Location::select('location')->distinct()->get();
        
        return view('admin::components.admin.sidebar', ['moviesales' => $moviesales, 'dates' => $dates, 'b' => $b, 'movienames' => $movienames, 'tickets' => $tickets, 'locations' => $locations]);
    }

    public function getHallSeatsData(Request $request)
    {
        $location = $request->get('location');
        $location_ids = Location::select('id')->where('location', $location)->get();
        $labels = Location::where('location', $location)->pluck('hall_name');
        $labels_id = Location::where('location', $location);
        $ticket_ids = Location::where('location', $location)->pluck('id');
        ticket::whereIn('location_id', $ticket_ids);
        $data = [];
        $data_outer = [];
        $movie_names = ticket::pluck('movie_name')->unique()->values();
        foreach ($movie_names as $movie_name) {
            foreach ($location_ids as $id) {
                array_push($data, Ticket::where('location_id', $id->id)->where('movie_name', $movie_name)->get()->count());
            }
            array_push($data_outer, $data);
            $data = [];
        }
        // dd($data_outer); 
        return response()->json([
            'labels' => $labels,
            'data' => $data_outer,
        ]);
    }


    public function chat(Request $request)
    {
        $user_id = $request->id;
        $tracked_list = TrackMessage::where('user_id', $user_id)->value('msg_list');
        if (is_null($user_id) or is_null($tracked_list)) {
            $tracked_list = [['type' => 'admin', 'msg' => 'begin conversation']];
        }
        $username = register::find($user_id);
        $userSelectedFlag = false;
        if (is_null($username)) {
            $username = 'user';
        } else {
            $username = $username->username;
            $userSelectedFlag = true;
        }
        return view('admin::components.admin.chat', ['users' => register::all(), 'tracklist' => $tracked_list, 'user_id' => $user_id, 'username' => $username,'userSelectedFlag'=>$userSelectedFlag]);
    }

    public function updateusers(Request $request)
    {
        // dd($request->all());
        $register = register::find($request->rowid);
        $register->update([$request->datatype => $request->value]);
        return response()->json(['status' => 'ok']);
    }

    public function revenue(RevenueDataTable $datatable)
    {
        return $datatable->render('admin::components.admin.revenue');
    }

    public function details(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $cost = MovieScreeningCost::where('movie_id', (int) $id)->pluck('screening_cost');
        $labels = RevenueSummary::where('movie_id', (int) $id)->pluck('date');
        $revenue_s = RevenueSummary::where('movie_id', (int) $id)->pluck('revenue');
        $labels = Revenue::where('movie_id', (int) $id)->distinct()->pluck('date')->sort()->values();
        $ticket_rate_ids = Revenue::where('movie_id', (int) $id)->distinct()->pluck('ticket_rate_id');
        // dd($ticket_rate_ids);
        $datasets = [];
        $LocationArray = [];
        foreach ($ticket_rate_ids as $ticket_rate_id) {
            $data = [];
            $movieTime = TicketRate::find($ticket_rate_id);
            $LocationId = Movietime::find($movieTime->movie_time_id)->location_id;
            if (in_array($LocationId,$LocationArray)){
                foreach ($labels as $date) {
                    $revenue = Revenue::where('date', $date)
                        ->where('movie_id', (int) $id)
                        ->where('ticket_rate_id', $ticket_rate_id)
                        ->pluck('revenue')
                        ->first();
                    // dd($revenue);
                    $data[] = $revenue ?? 0;
                }
                // dd($data);
                $LocationName = Location::find($LocationId)->location . Location::find($LocationId)->hall_name;
                // dd($data);
                $result = array_map(function ($a, $b) {
                    return $a + $b;
                }, $datasets[array_search($LocationId, $LocationArray)]['data'], $data);
                $datasets[array_search($LocationId, $LocationArray)]['data'] = $result;
            }else{
                array_push($LocationArray,$LocationId);
                foreach ($labels as $date) {
                    $revenue = Revenue::where('date', $date)
                        ->where('movie_id', (int) $id)
                        ->where('ticket_rate_id', $ticket_rate_id)
                        ->pluck('revenue')
                        ->first();
                        // dd($revenue);
                    $data[] = $revenue ?? 0;
                }
                // dd($data);
                $LocationName = Location::find($LocationId)->location . Location::find($LocationId)->hall_name;
                // dd($data);
                
                $datasets[] = [
                    'type' => 'line',
                    'label' => $LocationName,
                    'data' => $data,
                    'borderWidth' => 1
                ];
            }
    }
        // dd($data);
        // dd($revenue_s->toArray());
        $totalRevenue = array_sum($revenue_s->toArray());
        $reverseSum = array_reverse($revenue_s->toArray());
        $NetRevenue = [];
        $counter = 0;
        while($totalRevenue != 0){
            array_push($NetRevenue,$totalRevenue);
            $totalRevenue -= $reverseSum[$counter];
            $counter++;
        }
        $NetRevenue = array_reverse($NetRevenue);
        // dd($totalRevenue);

        return view('admin::admin.revenuegraph', [
            'id' => $request->id,
            'labels' => $labels,
            'datasets' => $datasets,
            'revenue' => $revenue_s,
            'cost' => $cost,
            'NetRevenue'=>$NetRevenue,
        ]);
    }

    public function halllist()
    {
        $halls = Location::all();
        // dd(json_decode($halls));
        return view('admin::admin.hallList', ['halls' => ($halls)]);
    }

    public function test()
    {
        StopBooking::dispatch();
        \Log::channel('custom')->info('job dispatched');
    }

    public function hallsave(Request $request)
    {
        $id = $request->input('rowid');
        $column = $request->input('dataindx');
        $value = $request->input('value');

        $location = Location::find($id);
        if ($location) {
            $location->update([$column => $value]);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Location not found'], 404);
        }
    }

    public function notifications()
    {
        return view('admin::admin.Notification');
    }

    public function salessearch(Request $request)
    {

        $min = $request->input('minsales');
        $max = $request->input('maxsales');
        $emails = ticket::groupBy('email')
            ->selectRaw('email')
            ->get();




        foreach ($emails as $key => $email) {

            $user = register::where('email', $email->email)->first();
            salesSearchEvent::dispatch($user, 'test');

            // if ($email->count >= $min and $email->count <= $max) {

                //event(new salesSearchEvent($user,'test')) doesnt work
            // }
        }
    }

    public function movieslist(){
        $movies = movie::all();
        return view('admin::admin.moviesList',['movies'=>$movies]);
    }

    public function editMovies(){
        $movies = movie::all();
        return view('admin::admin.editMovies',['movies'=>$movies]);
    }

    public function individualedit(Request $request){
       
        $movieId = $request['id'];
        $movie = movie::find($movieId);
        $screeningCost = MovieScreeningCost::where('movie_id',$movieId)->first();
        if(is_null($screeningCost)){
            return view('admin::admin.editPage', ['movie' => $movie, 'id' => $movieId,'screeningCost'=>0]);
        }
        $screeningCost = $screeningCost ->screening_cost;
        return view('admin::admin.editPage',['movie'=>$movie,'id'=>$movieId,'screeningCost'=>$screeningCost]);
    }

    public function add(Request $request){
        return view('admin::admin.addHalls');
    }

    public function saveHallData(Request $request){
        $newLocation = Location::create(['location'=>$request['hallLocation'],'hall_name'=>$request['hallName'],'seats'=>$request['hallSeatsNumber']]);
        if($newLocation){
            return redirect()->route('admin.addHalls')->with(['key'=>'successfully added new hall']);
        }
        return redirect()->route('admin.addHalls')->with(['key' => 'fail to added new hall']);
    }

    public function editHallsView(){
        $halls = Location::all();
        return view('admin::admin.editHalls',['halls'=>$halls]);
    }

    public function deleteHallRow(Request $request){
        $hallRowId = $request->rowid;
        
        $status = Location::where('id',$hallRowId)->delete();

        if ($status){
            return response()->json(['success'=>true,'message'=>'hall deleted successfull']);
        }else{
            return response()->json(['success'=>false,'message'=>'hall deleted fail']);
        }
    }

}
