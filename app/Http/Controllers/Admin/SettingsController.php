<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;use App\Models\SiteSetting;use Illuminate\Http\Request;
class SettingsController extends Controller {public function edit(){return view('admin.settings',['settings'=>SiteSetting::pluck('value','key')]);}public function update(Request $r){$data=$r->validate(['site_name'=>'nullable|string|max:150','hotline'=>'nullable|string|max:80','email'=>'nullable|email','address'=>'nullable|string|max:500','facebook'=>'nullable|url','twitter'=>'nullable|url','youtube'=>'nullable|url','footer_text'=>'nullable|string|max:500','map_embed'=>'nullable|string']);foreach($data as $k=>$v)SiteSetting::updateOrCreate(['key'=>$k],['value'=>$v]);return back()->with('success','Settings saved.');}}
