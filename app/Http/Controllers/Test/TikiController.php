<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\TempTes;
use App\Models\Question;
use App\Models\TesTemporary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TikiController extends Controller
{
    public function getData($part,$id){
        $soal = Question::where('packet_id','=',38)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);

        return response()->json([
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part
        ]);
    }


    public static function index(Request $request, $path, $test, $selection)
    {
        if($request->part == null){
            $part = 1;
        }else{
            $part = $request->part;
        }


        $packet = Packet::where('test_id','=',23)->where('status','=',1)->first();
        $soal = Packet::select('amount')->where('test_id','=',23)->where('part','=',$part)->first();
        // $soal = Question::where('packet_id','=',38)->where('number','=',$part)->first();
        // $decode_soal = json_decode($soal->description,true);

        // dd($decode_soal);
        // $jumlah_soal = count($decode_soal[0]['soal']);
        // $soal_c = self::soal();
        $jumlah_soal = $soal->amount;
        
        
        return view('test.tiki.tiki', [
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet,
            'jumlah_soal' => $jumlah_soal,
            'part'=>$part
        ]);
    }

    public static function store(Request $request){
        
        $path = $request->path;
        $test_id = $request->test_id;
        $selection = $request->selection;
        $part = ($request->part) + 1;
        $packet_id = $request->packet_id;
        $jawaban = json_encode($request->jawaban);

        dd($request->all());

        $save_sementara = new TesTemporary;
        $save_sementara->id_user = Auth::user()->id;
        $save_sementara->test_id = $test_id;
        $save_sementara->packet_id = $packet_id;
        $save_sementara->json = $jawaban;
        $save_sementara->part = $part;
        $save_sementara->save();

        $temporary_result = self::getTempt(Auth::user()->id,$test_id,$packet_id,$part);
        // dd($temporary_result);

        if($part >= 12){
            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
        }
        else{
            
            return redirect('/tes/tiki?part='.$part);
        }
    }

    public static function getTempt($id_user,$test_id,$packet_id,$part){

        $data = TesTemporary::where('id_user',$id_user)->where('test_id',$test_id)
                            ->where('packet_id',$packet_id)->where('part',$part)
                            ->get();
        return $data;
    }



    
    
    
    
    
    
    
    
    
    
    
        
    
}