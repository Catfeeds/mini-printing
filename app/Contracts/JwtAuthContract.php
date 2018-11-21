<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 10:15
 * Function:
 */

namespace App\Contracts;


use Illuminate\Http\Response;
use Illuminate\Http\Request;

interface JwtAuthContract
{
    public function login() : Response;

    public function refresh() : Response;

    public function logout() : Response;

    public function register(Request $request);

    public function checktoken(Request $request) :Response;

    public function mobile(Request $request);
}