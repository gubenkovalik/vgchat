<?php
namespace App\Http\Controllers;

use App\Http\User;

class NodeController extends Controller
{

    public function __construct()
    {

    }

    public function users_status_get()
    {
        header("Pragma: no-cache");
        header("Cache-Control: no-store,no-cache");


        $users = User::get();

        $statuses = [];

        foreach ($users as $u) {


            $statuses[$u->id] = (time() - strtotime($u->last_seen)) < (60 * 5);
        }


        $output = json_encode($statuses);

        header("Content-Type: application/json");
        header("Content-Length: " . strlen($output));
        header("Access-Control-Allow-Origin: *");

        echo $output;
        exit;
    }


}
