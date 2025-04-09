<?php
session_start();
include("headerb4login.html");

if(isset($_SESSION["username"])){
    header("location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="—Pngtree—water tap icon for your_4852220.png">

</head>
<style>
      * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
     body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            height: 100%;
        }
        .disdiv {
            flex: 1;
            display: flex;
            
            justify-content: center;
            padding: 20px;
            text-align: center;
        }

 .disp{
    position: relative;
    color:rgb(13, 19, 32);
    font-size: 50px;

    
 }
 .bottomdiv{
   
    background-color: #f0f0f0;
    
    padding: 20px;
    text-align: center;
    border-radius: 5px 5px 0 0;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    display: flex;

 }
 .inbottomdiv{
    width: 50%;
    margin: auto;
 }
 .inbottom  p{
    margin: 10px;

 }
 @media ( max-width: 768px) {
    .bottomdiv{
        display: block;
        }

    }        
  
</style>
<body>
    <div class="disdiv">
    <h1 class="disp">PURE WATER FOR EVERYONE</h1>
    </div>
    <div class="bottomdiv"> 
        <div class="inbottomdiv">
        <h1 id="about"><u>About us</u></h1>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. At dolore modi culpa dolor consectetur consequatur, hic facere illo, dignissimos dicta quos laudantium temporibus necessitatibus, porro aperiam. Suscipit officiis explicabo ducimus!</p><br><br>
        <img src="image3.jpg" alt="" width="200px">
        </div>
        <div class="inbottomdiv">
            <h1><u>More info</u></h1>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repellat, corrupti recusandae eos iusto voluptas modi aliquam numquam quaerat. Accusamus amet dignissimos eos sapiente, quisquam rerum esse quasi fugit perspiciatis dolorem.</p>
            <br><br>
            <img src="image3.jpg" alt="" width="200px">
        </div>
        <div class="inbottomdiv">
            <h1 id="contact"><u>Contact us</u></h1>
            <p>you can contact us at <a href="mailto:info@purewater.com">contact</a>
            </p><br><br>
            <img src="image3.jpg" alt="" width="200px">
        </div>
       
    </div>
    

    
</body>
</html>
<?php

   
?>