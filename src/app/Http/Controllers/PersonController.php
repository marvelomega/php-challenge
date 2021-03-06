<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Person;
use App\Http\Models\Phone;
use Laracasts\Flash;

class PersonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $person = Person::all();
        return view('people', with(['person'=>$person]));
    }

    public function upload(Request $request)
    {

        try{

            $process = $this->processXML($request);

            $arrayJson = [];
            if($process['return']==false){
                throw new \Exception($process['message'],1);
            }else{
                $arrayJson = $process['array'];
            }

            if(sizeof($arrayJson)==0){
                throw new \Exception("Arquivo não pode ser processado", 1);
            }


            foreach ($arrayJson as $key => $ajs) {
                $person = new Person([
                    "personname"  => $ajs["personname"],
                    "personid"    => $ajs["personid"],
                ]);

                $save = $person->save();

                if($save==false) {
                    throw new \Exception('Erro ao Salvar!');
                }

                if(isset($ajs['phones'])){
                    foreach ($ajs['phones'] as $kk => $phones) {
                        if(!is_array($phones)){
                            $phonesaux[0] = $phones;
                            $phones = $phonesaux;
                        }

                        foreach ($phones as $k => $p) {
                            $phone = new Phone([
                                "phone"        => $p,
                                "person_id"    => $person->id,
                            ]);

                            $savePhone = $phone->save();
                            if($savePhone==false) {
                                throw new \Exception('Erro ao Salvar o telefone!');
                            }
                        }
                    }
                }
            }

            \flash('Success')->success();
            return redirect()->route('person.index');


        }catch (\Exception $e){
            \flash($e->getMessage())->error();
            return redirect(route('person.index'));

        }
    }
}
