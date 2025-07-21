<?php

$host="localhost";
$username="root";
$password="12332112";
$dbname="dashbord";

$con=new mysqli($host,$username,$password,$dbname);

if($con->connect_error){
    echo "connection failed : ".$con->connect_error;
    exit();
}
