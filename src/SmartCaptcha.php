<?php
/**
 * SmartCaptchaLib v1.0
 *
 * Description of SmartCaptcha
 * This class is designed to make random captcha code by your oponion
 * it extended from CaptchaImage class.
 *
 * @author Seyed Mohammad Moein Hoseini Manesh < www.moeinhm.name >
 * @author mail moein7tl@gmail.com
 *
 *  With special thanks to Ali Akbari Boromand and Hossein Bokhamsein
 */

require_once 'CaptchaImage.php';

class SmartCaptcha extends CaptchaImage {

    /**
     * Probability number
     */
    private $probbility_num;

    /**
     * Probability symbols
     */
    private $probbility_symbols;

    /**
     * Probability space
     */
    private $probbility_space;

    /**
     * CAPTCHA length
     */
    private $len;

    /**
     * Constructor
     *
     * @param Integer $width
     * @param Integer $height
     * @param Integer $len
     *
     * @return void
     */
    public function  __construct($width = 215,$height = 100,$len = 6) {
        parent::__construct($width, $height);
        $this->setLen($len);           // set length of the captcha code
        $this->SetProbbility(0, 0, 0); // set probbility of numbers, symboles and space in captcha code
        $this->newText();              // make random captcha code
    }
    
    /**
     * Random text
     *
     * Return a random captcha code by probability of 
     * numbers, symbols and spaces
     *
     * @param Integer $length
     * 
     * @return String $string
     */
    private function RandomText($length = 6){
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

    /**
     * Set probability
     *
     * This method is use to set probbility of numbers ,symbol and space in captcha code
     * by default,they are 0
     * if use this ,it will make new captcha code too
     *
     * @param Integer $number
     * @param Integer $symbole
     * @param Integer $space
     *
     * @return void
     */
    public function SetProbbility($number = 0,$symbol = 0,$space = 0){
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

    /**
     * Random number
     *
     * This method,will return number of char
     *
     * @return Integer (0-9)
     */
    public function RandomNumber(){
        return (chr(rand(48, 57)));
    }

    /**
     * Next text
     *
     * Generate random captcha
     * @return void
     */
    public function newText(){
       $this->Text = $this->RandomText($this->len);
    }

    /**
     * Set Length of CAPTCHA
     *
     * @param Integer $len
     *
     * @return void
     */
    public function setLen($len){
        if ($len >= 20 || $len <= 2)
            $this->len = 6;
        else
            $this->len = $len;
            $this->newText();
    }

    /**
     * Random alphabet
     *
     * @return String [A-Za-z]
     */
    private function RandomAlphaBet(){
        if (rand(0, 1) == 0) return (chr(rand(65,90))); // upercase
        else return (chr(rand(97,122))); // lowercase
    }

    /**
     * Random symbols
     *
     * This method will return some of symboles
     *
     * @return String
     */
    private function RandomSymbols(){
        $randnum = rand(0,22);
        if ($randnum <= 5) $randnum += 33;
        else if ($randnum >= 6 && $randnum <= 9) $randnum += 36;
        else if ($randnum >= 10 && $randnum <= 16) $randnum += 48;
        else if ($randnum >= 17 && $randnum <= 19) $randnum += 74;
        else $randnum += 103;
        
        return (chr($randnum));
    }

    /**
     * Get text
     *
     * Return captcha code for save in session
     * or insert into database or keep in value
     * it's better to use it directly befor or after draw method
     * @return String $text
     */
    public function getText(){
        return $this->Text;
    }

    /**
     * Set text
     *
     * @param String $text
     * @return void
     */
    public function setText($string){
        $this->Text = $string;
    }
}
?>
