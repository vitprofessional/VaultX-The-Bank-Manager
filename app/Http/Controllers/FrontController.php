<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountList;
use App\Models\BankEmployee;

class FrontController extends Controller
{
    public function accountCreation(){
        return view('adminPanel.accountCreation');
    }
    public function aclist(){
        return view('adminPanel.accountList');
    }

    public function saveAccount(Request $requ){
        $chk = AccountList::where(['acNumber'=>$requ->acNo])->get();
        if(!$chk->isEmpty()):
            return back()->with('error','Sorry! User already exist');
        endif;

        $data = new AccountList();

        $data->acName       = $requ->acName;
        $data->acNumber     = $requ->acNo;
        $data->acType       = $requ->acType;
        $data->acMobile     = $requ->acMobile;
        $data->acFinger     = $requ->acFinger;
        $data->employee_id  = $requ->employeeId;

        if($data->save()):
            return back()->with('success','Success! Account creation successfully');
        else:
            return back()->with('error','Opps! Account creation failed. Please try later');
        endif;
        
    }

    public function acView($id){
        $acData = AccountList::find($id);
        return view('adminPanel.accountGenerate',['data'=>$acData]);
    }

    public function acEdit($id){
        $acData = AccountList::find($id);
        return view('adminPanel.accountUpdate',['data'=>$acData]);
    }

    public function acUpdate(Request $requ){
        $data = AccountList::find($requ->acId);
        if(isset($data)):
            $data->acName       = $requ->acName;
            $data->acNumber     = $requ->acNo;
            $data->acType       = $requ->acType;
            $data->acMobile     = $requ->acMobile;
            $data->acFinger     = $requ->acFinger;
            $data->employee_id  = $requ->employeeId;

            if($data->save()):
                return back()->with('success','Success! Account update successfully');
            else:
                return back()->with('error','Opps! Account failed to update. Please try later');
            endif;
        else:
            return back()->with('error','Sorry! no data found');
        endif;
        
    }

    public function acDel($id){
        $acData = AccountList::find($id);
        if(!empty($acData)):
            if($acData->delete()):
                return back()->with('success','Records successfully deleted');
            endif;
        endif;

        return back()->with('error','No records found for deleted');
    }

    public function acDelete($id){
        return $this->acDel($id);
    }

    public function editEmployee($id){
        $bankEmployee = BankEmployee::find($id);
        return view('adminPanel.createEmployee',['profile'=>$bankEmployee]);
    }

    public function delEmployee($id){
        $bankEmployee = BankEmployee::find($id);
        if($bankEmployee->delete()):
            return back()->with('success','Records successfully deleted');
        else:
            return back()->with('error','No records found for deleted');
        endif;
    }

}
