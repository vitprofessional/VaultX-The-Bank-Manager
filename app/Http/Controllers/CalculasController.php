<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankCapital;
use App\Models\BankEmployee;
use Session;
use Hash;

class CalculasController extends Controller
{
    public function index(){
        return view('adminPanel.home');
    }
    
    public function editCalculas($id){
        // $capitalId  = $id;
        $dateToday  = date('Y-m-d');
        $data   = BankCapital::find($id);
        return view('adminPanel.home',['data'=>$data,'capitalId'=>$id]);
    }
    
    public function saveCalculas(Request $requ){
        $employee_id        = $requ->employeeId;

        $data = new BankCapital();
        $data->ob           = $requ->liquid;
        $data->cb           = $requ->handCash;
        $data->employee_id  = $requ->employeeId;
        if($data->save()):
            return back()->with('success','Capital details saved successfully');
        else:
            return back()->with('error','Sorry! There was an error. Please try later');
        endif;            
    }
    
    public function updateCalculas(Request $requ){
        $data               = BankCapital::find($requ->capitalId);
        $data->ob           = $requ->liquid;
        $data->cb           = $requ->handCash;
        $data->lastBalance  = $requ->lastBalance;
        if($data->save()):
            return back()->with('success','Capital details update successfully');
        else:
            return back()->with('error','Sorry! There was an error. Please try later');
        endif;            
    }
    
    public function debitCredit(){
        return view('adminPanel.debitCredit');
    }
    
    public function bankEmployee(){
        if(Session::has('superAdmin')):
            $employeeId = BankEmployee::find(Session::get('superAdmin'))->id;
        elseif(Session::has('generalAdmin')):
            $employeeId = BankEmployee::find(Session::get('generalAdmin'))->id;
        elseif(Session::has('manager')):
            $employeeId = BankEmployee::find(Session::get('manager'))->id;
        else:
            $employeeId = BankEmployee::find(Session::get('cashier'))->id;
        endif;


        $profile = BankEmployee::find($employeeId);
        if($profile->profileType == 1):
            $bankEmployee = BankEmployee::orderBy('id','desc')->get();
            return view('adminPanel.createEmployee',['data'=>$bankEmployee]);
        elseif($profile->profileType == 2 || $profile->profileType == 3):
            $bankEmployee = BankEmployee::where(['creator'=>$employeeId])->orderBy('id','desc')->get();
            return view('adminPanel.createEmployee',['data'=>$bankEmployee]);
        else:
            return "<div class='alert alert-info'>Sorry! you are not eligable to view this page</div>";
        endif;
    }

    public function employeeRegister(Request $requ){
        if($requ->profileId):
            $data = BankEmployee::find($requ->profileId);
        else:
            $chk = BankEmployee::where(['employeeId'=>$requ->loginId])->get();
            if(!empty($chk) && $chk->count()>0):
                return back()->with('error','Sorry! Employee already exist');
            endif;
            $data = new BankEmployee();
        endif;
        $data->name         = $requ->employeeName;
        $data->email        = $requ->employeeMail;
        $data->employeeId   = $requ->loginId;
        $data->profileType  = $requ->profileType;
        if($requ->loginPass):
            $hashPass           = Hash::make($requ->loginPass);
            $data->password     = $hashPass;
            $data->creator      = $requ->employeeId;
        endif;
        if($data->save()):
            return back()->with('success','Success! Employee details saved successfully');
        else:
            return back()->with('error','Sorry! Employee details failed to save');
        endif;
    }

    public function changeUserPass(){
        return view('adminPanel.updatePassword');
    }

    public function updatePassword(Request $requ){
        if($requ->newPass != $requ->confirmPass):
            return back()->with('error','New password not match confirm password');
        endif;
        $chk = BankEmployee::find($requ->employeeId);
        if(!empty($chk)):
            $hashPass   = $chk->password;
            if(Hash::check($requ->loginPass,$hashPass)):
                $newHashPass = Hash::make($requ->newPass);
                $chk->password = $newHashPass;
                $chk->save();
                return back()->with('success','Password changed successfully');
            else:
                return back()->with('error','Old password not match');
            endif;
        else:
            return back()->with('error','Sorry! profile not found for update');
        endif;
    }

    public function userProfile(){
        return view('adminPanel.profile');
    }

    public function updateEmployeeProfile(Request $requ){
        $chk = BankEmployee::find($requ->employeeId);
        if(!empty($chk)):
            $chk->name      = $requ->employeeName;
            $chk->email     = $requ->employeeMail;
            $chk->mobile    = $requ->employeeMobile;
            $chk->save();
            
            return back()->with('success','Profile changed successfully');
        else:
            return back()->with('error','Sorry! profile not found for update');
        endif;
    }
}
