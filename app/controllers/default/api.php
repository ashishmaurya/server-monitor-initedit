<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @TODO   Create Api is required
 */
class api extends ApiController
{

    public function index()
    {
        header('Content-Type: application/json');
        $apiApp = new ApiApp;
    }
}