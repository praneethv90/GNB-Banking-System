<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location:../ATM.php');

    exit;
}else{
    

    $fname = $_POST['clientFName'];
    $lname = $_POST['clentsLName'];
    $accountNo = $_POST['account_balance'];
    $accountNo = $_POST['accountNo'];

    echo $fname; 
    echo $lname; 
    echo $accountNo; 
    echo $accountNo; 


}