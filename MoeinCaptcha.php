<?php

/*
 * MoeinCaptchaLib v1.0
 */

/*
 * Description of MoeinCaptcha
 * This class is designed to make random captcha code by your oponion
 * it extended from CaptchaImage class.
 * @author Seyed Mohammad Moein Hoseini Manesh
 
 * @author mail moein7tl@gmail.com
 *  With special thanks to Ali Akbari Boromand and Hossein Bokhamsein
 */

require_once 'CaptchaImage.php';

class MoeinCaptcha extends CaptchaImage {

    private $probbility_num,$probbility_symbols,$probbility_space;
    private $len;
    public function  __construct($width = 215,$height = 100,$len = 6) {
        parent::__construct($width, $height);
        $this->setLen($len);           // set length of the captcha code
        $this->SetProbbility(0, 0, 0); // set probbility of numbers, symboles and space in captcha code
        $this->newText();              // make random captcha code
    }
    
    private function RandomText($length = 6){
        /*
         * This function is returning random captcha code by probbility of numbers,symbols and space
         */
        $string = "";
        $rand = 0;
        for ($i = 0; $i < $length; $i++){
            $rand = rand(1, 100);
            if ($rand >= 1 && $rand <= $this->probbility_num)
                    $string .= $this->RandomNumber();
            else if ($rand > $this->probbility_num && $rand <= ($this->probbility_num + $this->probbility_symbols))
                    $string .= $this->RandomSymbols();
            else if ($rand > ($this->probbility_num + $this->probbility_symbols) && $rand <= ($this->probbility_num + $this->probbility_symbols + $this->probbility_space))
                    $string .= " ";
            else
                    $string .= $this->RandomAlphaBet();
        }
        return $string;
    }
    public function SetProbbility($number = 0,$symbol = 0,$space = 0){
        /*
         * This method is use to set probbility of numbers ,symbol and space in captcha code
         * by default,they are 0
         * if use this ,it will make new captcha code too
         */
        settype($number, "int");
        settype($symbol, "int");
        settype($space, "int");
        $number = abs($number);
        $symbol = abs($symbol);
        $space = abs($space);
        if ($number + $symbol + $space <= 100){
            $this->probbility_num     = $number;
            $this->probbility_symbols = $symbol;
            $this->probbility_space = $space;
        } else {
            $this->probbility_num     = 0;
            $this->probbility_symbols = 0;
            $this->probbility_space   = 0;
        }
       $this->newText();
    }

    private function RandomNumber(){
        //This method,will return number of char
        return (chr(rand(48, 57)));
    }
    public function newText(){
        //generate random captcha
       $this->Text = $this->RandomText($this->len);
    }
    public function setLen($len){
        //set length of captcha code
        if ($len >= 20 || $len <= 2)
            $this->len = 6;
        else
            $this->len = $len;
        $this->newText();
    }
    private function RandomAlphaBet(){
        //This method will return a-z /A-Z
        if (rand(0, 1) == 0) // Captal
            return (chr(rand(65,90)));
        else
            return (chr(rand(97,122)));
    }

    private function RandomSymbols(){
        //This method,will return some of symboles
        $randnum = rand(0,22);
        if ($randnum <= 5)
            $randnum += 33;
        else if ($randnum >= 6 && $randnum <= 9)
            $randnum += 36;
        else if ($randnum >= 10 && $randnum <= 16)
            $randnum += 48;
        else if ($randnum >= 17 && $randnum <= 19)
            $randnum += 74;
        else
            $randnum += 103;
        
        return (chr($randnum));
    }

    public function getText(){
        // return captcha code for save in session or insert into database or keep in value
        // it's better to use it directly befor or after draw method
        return $this->Text;
    }
}
?>
