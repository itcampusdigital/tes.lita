<?php

namespace App\Http\Controllers\Test;

use Auth;
use App\Models\Packet;
use App\Models\Result;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssesmentController extends Controller
{    
    /**
     * Display
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request, $path, $test, $selection)
    {
        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            // Get the packet and questions
            $packet = Packet::where('test_id','=',11)->where('status','=',1)->first();
            $questions = $packet ? $packet->questions()->first() : [];
            $questions->description = json_decode($questions->description, true);
    
            // View      
            return view('test/'.$path, [
                'packet' => $packet,
                'path' => $path,
                'questions' => $questions,
                'selection' => $selection,
                'test' => $test,
            ]);
        }
    }

    public function getData($num){
        $quest = Question::with('packet')
                                ->whereHas('packet', function($query){
                                    return $query->where('test_id','=',11)->where('status','=',1);
                                })->first();
                                
        $quest->description = json_decode($quest->description, true);

        return response()->json([
            'quest' => $quest,
        ]);
    }

    /**
     * Store
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
        $test_id = $request->test_id;
        $cek_test = existTest($test_id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }

        else{
            
            // Get the packet and questions
            $packet = Packet::where('test_id','=',$request->test_id)->where('status','=',1)->first();
            
            // Get jawaban
            if($request->path == 'assesment'){
                $hasil = $request->get('p');
            }
            if($request->path == 'assesment-01'){
                $hasil = $request->get('inputQ');
            }
            
            foreach($hasil as $key=>$value){
                $array[$key-1]['jawaban'] = $value;
            }
            
            $test = array();
            for($i=0; $i<=13; $i++){
                array_push($test,$array[$i]['jawaban']);
            }
    
            // Hitung jumlah jawaban
            $jumlahA = 0;
            $jumlahB = 0;
            $jumlahC = 0;
            for($i=0; $i<=13; $i++){
                if($test[$i] == "A"){
                    $jumlahA++;
                }
                else if($test[$i] == "B"){
                    $jumlahB++;
                }
                else if($test[$i] == "C"){
                    $jumlahC++;
                }
                else{
                    $i++;
                }
            }
    
            // Kesimpulan 
            $result = array();
            for($i=0; $i<=13; $i++){
                array_push($result, $array[$i]['jawaban']);
            }
            // Deskripsi kesimpulan
                if($jumlahA > $jumlahB && $jumlahA > $jumlahC){
                    // Jumlah A lebih banyak
                    $result = "Visual";
                } elseif($jumlahB > $jumlahC && $jumlahB > $jumlahA) {
                    // Jumlah B lebih banyak
                    $result = "Auditory";
                } elseif($jumlahC > $jumlahA && $jumlahC > $jumlahB) {
                    //Jawaban C lebih banyak
                    $result = "Kinestetik";
                } elseif($jumlahA == $jumlahB && $jumlahA == $jumlahC) {
                    //Jawaban A = B = C sama banyak
                    $result = "Autocrat";
                } elseif($jumlahA == $jumlahB) {
                    //Jawaban A & B sama banyak
                    $result = "Visual Auditory";
                } elseif($jumlahB == $jumlahC) {
                    //Jawaban B & C sama banyak
                    $result = "Auditory Kinestetik";
                } elseif($jumlahC == $jumlahA) {
                    //Jawaban C & A sama banyak
                    $result = "Visual Kinestetik";
                }else{
                    $result = "Hasil tidak ditemukan";
                }
    
            // // Result
            $array = array(
                'result' => $result,
                'answers' => $hasil
            );
    
            // Save the result
            $result = new Result;
            $result->user_id = Auth::user()->id;
            $result->company_id = Auth::user()->attribute->company_id;
            $result->test_id = $request->test_id;
            $result->packet_id = $request->packet_id;
            $result->result = json_encode($array);
            $result->save();
    
            // Return
            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes '.$packet->test->name]);

        }
    }
}