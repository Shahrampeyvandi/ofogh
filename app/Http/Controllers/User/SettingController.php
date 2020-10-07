<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class SettingController extends Controller
{
    public function Setting()
    {
        $settings = DB::table('setting')->get();

        $setting=$settings[0];

       // dd($setting[0]->zaribsetaremoshtari);

        return view('User.Setting', compact('setting'));
    }

    public function SettingChange(Request $request)
    {

        $validation = $this->getValidationFactory()->make($request->all(), [
            'zarib_setare_moshtari' => 'required',
            'zaribtedadsarevaghtresidan' => 'required',
            'zaribetedadedirresidan' => 'required',
            'zaribetedadeghatehayecancelshode' =>'required',
            'zaribetedadeshoroebkarcancellshode' => 'required',
            'zaribetedadepishnaddarnmah' => 'required',
            'zaribetedadkarsabtnamavalie' =>'required',
            'emtiazkhedmatresanhad1' => 'required',
            'emtiazkhedmatresanhad2' => 'required',
            'emtiazkhedmatresanhad3' => 'required',
            'tedadrooztaligh' => 'required',
            'linkfaq' => 'required',
            'linklaw' => 'required',
            'linkappservicer' => 'required',
            'shomareoperator' => 'required',
            'shomareposhtibani' => 'required',
            'telegramposhtibani' => 'required',


        ]);

        if ($validation->fails()) {

          alert()->error('تمامی فیلد ها به صورت کامل و صحیح پر شوند!', 'ذخیره نشد')->autoclose(2000);

          return back();

        }


        $settings = DB::table('setting')
        ->where('id', 1)
        ->update([
            'zaribsetaremoshtari' => $request->zarib_setare_moshtari,
            'zaribtedadsarevaghtresidan' => $request->zaribtedadsarevaghtresidan,
            'zaribetedadedirresidan' => $request->zaribetedadedirresidan,
            'zaribetedadeghatehayecancelshode' => $request->zaribetedadeghatehayecancelshode,
            'zaribetedadeshoroebkarcancellshode' => $request->zaribetedadeshoroebkarcancellshode,
            'zaribetedadepishnaddarnmah' =>$request->zaribetedadepishnaddarnmah,
            'zaribetedadkarsabtnamavalie' => $request->zaribetedadkarsabtnamavalie,
            'emtiazkhedmatresanhad1' => $request->emtiazkhedmatresanhad1,
            'emtiazkhedmatresanhad2' => $request->emtiazkhedmatresanhad2,
            'emtiazkhedmatresanhad3' => $request->emtiazkhedmatresanhad3,
            'tedadrooztaligh' => $request->tedadrooztaligh,
            'linkfaq' => $request->linkfaq,
            'linklaw' => $request->linklaw,
            'linkappservicer' =>$request->linkappservicer,
            'shomareoperator' =>$request->shomareoperator,
            'shomareposhtibani' => $request->shomareposhtibani,
            'telegramposhtibani' => $request->telegramposhtibani,

            ]);



          

        alert()->success('با موفقیت کامل اطلاعات به روز رسانی گردید!', 'ذخیره شد')->autoclose(2000);

        return back();
    }
}
