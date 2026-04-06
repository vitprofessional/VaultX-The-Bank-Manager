<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServerConfig;
use File;

class ServerConfiguration extends Controller
{
    public function serverConfig(){
        $config = ServerConfig::first();
        return view('adminPanel.configuration',['config'=>$config]);
    }

    public function saveServerConfig(Request $requ){
        if(!empty($requ->businessId)):
            $server = ServerConfig::find($requ->businessId);
        else:
            $server = new ServerConfig();
        endif;

        $server->business_name      = $requ->businessName;
        $server->location           = $requ->businessLocation;
        $server->license_no         = $requ->licenseNo;
        $server->contact_number     = $requ->contactNumber;
        $server->email              = $requ->emailAddress;
        $server->bank_name          = $requ->bankName;
        $server->linked_branch      = $requ->linkedBranch;
        $server->thana              = $requ->businessThana;
        $server->district           = $requ->businessDistrict;
        $server->branch_district    = $requ->branchDistrict;
        $server->routing_number     = $requ->routingNumber;
        $server->swift_code         = $requ->bankSwift;
        $server->helpline           = $requ->bankHelpline;
        $server->employee_id        = $requ->businessId;

        // if configuration data save or not
        if($server->save()):
            return back()->with('success','Data saved successfully');
        else:
            return back()->with('error','Data failed to save');
        endif;
    }

    public function saveBankLogo(Request $requ){
        if(!empty($requ->businessId)):
            $server = ServerConfig::find($requ->businessId);
        else:
            $server = new ServerConfig();
        endif;
        request()->validate([
            'bankLogo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:300',
        ]);

        // check if logos are available
        if($requ->bankLogo):
            $bankLogo = time().'.'.request()->bankLogo->getClientOriginalExtension();
            request()->bankLogo->move(public_path('upload/logos'), $bankLogo);

            $server->bank_logo = $bankLogo;
        endif;

        // if configuration data save or not
        if($server->save()):
            return back()->with('success','Data saved successfully');
        else:
            return back()->with('error','Data failed to save');
        endif;
    }

    public function saveSecondLogo(Request $requ){
        if(!empty($requ->businessId)):
            $server = ServerConfig::find($requ->businessId);
        else:
            $server = new ServerConfig();
        endif;
        request()->validate([
            'secondLogo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:300',
        ]);
        // check if second logo available
        if($requ->secondLogo):
            $logo2 = time().'.'.request()->secondLogo->getClientOriginalExtension();
            request()->secondLogo->move(public_path('upload/logos'), $logo2);
            $server->logo_2 = $logo2;
        endif;

        // if configuration data save or not
        if($server->save()):
            return back()->with('success','Data saved successfully');
        else:
            return back()->with('error','Data failed to save');
        endif;
    }

    public function saveThirdLogo(Request $requ){
        if(!empty($requ->businessId)):
            $server = ServerConfig::find($requ->businessId);
        else:
            $server = new ServerConfig();
        endif;
        request()->validate([
            'thirdLogo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:300',
        ]);
        // check if third logo available
        if($requ->thirdLogo):
            $logo3 = time().'.'.request()->thirdLogo->getClientOriginalExtension();
            request()->thirdLogo->move(public_path('upload/logos'), $logo3);
            $server->logo_3 = $logo3;
        endif;

        // if configuration data save or not
        if($server->save()):
            return back()->with('success','Data saved successfully');
        else:
            return back()->with('error','Data failed to save');
        endif;
    }

    public function delBankLogo($id){
        $chk = ServerConfig::find($id);
        if(!empty($chk)):
            if (File::exists(public_path('upload/logos/'.$chk->bank_logo))) {
                File::delete(public_path('upload/logos/'.$chk->bank_logo));
            }
            
            $chk->bank_logo = "";
            $chk->save();
            return back()->with('success','Success! Picture deleted successfully');
        else:
            return back()->with('error','Opps! Picture failed to delete');
        endif;
    }

    public function delSecondLogo($id){
        $chk = ServerConfig::find($id);
        if(!empty($chk)):
            // return public_path('upload/logos/'.$chk->logo_2);
            if (File::exists(public_path('upload/logos/'.$chk->logo_2))) {
                File::delete(public_path('upload/logos/'.$chk->logo_2));
            }
            
            $chk->logo_2 = "";
            $chk->save();
            return back()->with('success','Success! Picture deleted successfully');
        else:
            return back()->with('error','Opps! Picture failed to delete');
        endif;
    }

    public function delThirdLogo($id){
        $chk = ServerConfig::find($id);
        if(!empty($chk)):
            if (File::exists(public_path('upload/logos/'.$chk->logo_3))) {
                File::delete(public_path('upload/logos/'.$chk->logo_3));
            }
            
            $chk->logo_3 = "";
            $chk->save();
            return back()->with('success','Success! Picture deleted successfully');
        else:
            return back()->with('error','Opps! Picture failed to delete');
        endif;
    }
}
