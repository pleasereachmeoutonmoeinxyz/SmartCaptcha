<?php
/**
 * SmartCaptcha
 *
 * Description of CaptchaImage
 * This class is desinged to make image for Captcha code.
 * This class is reading folder Fonts,you can add any font to this class easily
 * by copy / past your ttf files into Fonts directory
 *
 * @author Seyed Mohammad Moein Hoseini Manesh
 * @author mail moein7tl@gmail.com
 *
 */

class CaptchaImage {

    /**
     * Keep fonts for use in CAPTCHA image
     */
    private $fonts = array();

    /**
     * Fonts Directory
     */
    private $fonts_dir = 'Fonts';

    /**
     * Width and height of Image
     * If you don't use background you can set it directly
     */
    private $width, $height;

    /**
     * Background RGB
     */
    private $Color_R = null, $Color_G = null, $Color_B = null;

    /**
     * Text RGB
     */
    private $TC_R = null, $TC_G = null, $TC_B = null;

    /**
     * Text HSL
     */
    private $HSL_H, $HSL_S, $HSL_L;

    /**
     * HSL complement
     */
    private $HSL_H_complement;

    /**
     * CAPTCHA difficulity
     */
    private $Difficultly = 5;

    /**
     * CAPTCHA printed character angle
     */
    private $angle = 16;

    /**
     * Background Image
     */
    private $BackgroundImage = null;

    /**
     * Image Suffix
     */
    private $ImageSuffix = null;

    /**
     * Count of fonts which can be used
     */
    private $font_can_be_use_count;

    /**
     * Font can be added by user
     */
    private $font_can_be_user_src = array();

    /**
     * CAPTCHA Text
     */
    protected  $Text;

    /**
     * Constructor
     *
     * @param Integer $width
     * @param Integer $height
     *
     * @return void      
     */
    public function  __construct($width = 215, $height = 100) {
        $this->getFonts();                // get font address in fonts array
        $this->SetSize($width, $height);  // set size of the Image
        $this->SetDifficultly(5);         // set difficulty of Captcha/realy its angle
        $this->setHowManyFontCanBeUse(3); // by default,Captcha code can use 3 fonts
    }

    /**
     * Set size of CAPTCHA image
     *
     * @param Integer $width
     * @param Integer $height
     *
     * @return void
     */
    public function SetSize($width, $height){
        /*
         * default size is 215 * 100,
         * if you don't set it in constructor or by calling this method,
         * it will be 215 * 100
         * if width is smaller than 50, it will be 215
         * if height is smaller than 25, it will be 100
         */
        $width = round($width);
        $height = round($height);

        // check width
        if ($width >= 50) $this->width = $width;
        else $this->width = 215;

        // check height
        if ($height >= 25) $this->height = $height;
        else $this->height = 100;
    }
    
    /**
     * Set how many font can be used in CAPTCAH image
     *
     * @param Integer $font_can_be_use
     *
     * @return void
     */
    public function setHowManyFontCanBeUse($font_can_be_use){
        /*
         * set how many random fonts can be use in Captcha Image
         * by default it set 3
         */
        if ($font_can_be_use <= count($this->fonts) && $font_can_be_use >= 0)
            $this->font_can_be_use_count = $font_can_be_use;
        else
            $this->font_can_be_use_count = count($this->fonts);
    }

    /**
     * Set background image
     *
     * @param String $address of file
     *
     * @return void
     */
    public function SetBackGroundImage($address){
        /*
         * by calling this method ,you can set Image address
         * it should be correct image file otherwise it will be set as null
         */
        $suffix = substr($address,strrpos($address,"." ,-1) + 1);
        $suffix = strtolower($suffix);
        $this->ImageSuffix = $suffix;
        if (@(is_file($address) && file_exists($address) && ($suffix == "gif" || $suffix == "png" || $suffix == "jpg")))
            $this->BackgroundImage = $address;
        else
            $this->BackgroundImage = null;
    }
    
    /**
     * Set background by RGB code
     *
     * @param Integer $red
     * @param Integer $green
     * @param Integer $blue
     *
     * @return void
     */
    public function SetBackGround($red = 255, $green = 255, $blue = 255){
        /*
         * set true background
         * if you don't do it,it will be white as default
         * you can make random background,by calling this method is uncorrect valua,like 1000,1000,1000
         */
        $this->RGBCheker($red, $green, $blue);
        $this->Color_R = $red;
        $this->Color_G = $green;
        $this->Color_B = $blue;
    }
    
    /**
     * Set text color by RGB code
     *
     * @param Integer $red
     * @param Integer $green
     * @param Integer $blue
     *
     * @return void
     */
    public function SetTextColor($red = 0,$green = 0,$blue = 0){
        /*
         * set true text color 
         * if you don't do it,it will be black as default
         * you can make random text color,by calling this method is uncorrect valua,like 1000,1000,1000
         */
        $this->RGBCheker($red, $green, $blue);
        $this->TC_R = $red;
        $this->TC_G = $green;
        $this->TC_B = $blue;
    }
    
    /**
     * Set difficulity of CAPTCHA
     *
     * @param Integer $Difficulty code (1-10)
     *
     * @return void
     */
    public function SetDifficultly($Difficultly){
        /*
         * you can set difficulty from 1 to 10
         * it will set angle from 0(by 1) to 36(10)
         * by default,it set to 16(5)
         */
        if ($Difficultly >= 1 && $Difficultly <= 10)
            $this->Difficultly = $Difficultly;
        else
            $this->Difficultly = 5;
        switch ($this->Difficultly){
            case 1:
                $this->angle = 0;
                break;
            case 2:
                $this->angle = 4;
                break;
            case 3:
                $this->angle = 8;
                break;
            case 4:
                $this->angle = 12;
                break;
            case 5:
                $this->angle = 16;
                break;
            case 6:
                $this->angle = 20;
                break;
            case 7:
                $this->angle = 24;
                break;
            case 8:
                $this->angle = 28;
                break;
            case 9:
                $this->angle = 32;
                break;
            case 10:
                $this->angle = 36;
                break;
            default:
                $this->angle = 16;
        }      
    }

    /**
     * Get fonts from fonts directory
     *
     * @return Boolean
     */
    private function getFonts(){
      // This is method to store fonts name from fonts_dir dircetory 
      // default directory is ./Fonts
      // Return True, if can find font otherwise return false
        $count = 0;
        if ($handle = @opendir($this->fonts_dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $this->fonts[$count++] = $file;
                }//end of if , for checking its not . and ..
            }//end of while
        }// can open Fonts folder
        closedir($handle);
        if ($handle == FALSE)
            return FALSE;
        return TRUE;
    }

    //+ ---------------------- @Moein: I wanna call it XPart

    /**
     * XPart Description
     *
     * You can call a color by RGB or HSL
     * this part is try to find Complementary Color
     * First,change Background Color to HSL ,then trun Hue value 180 degree
     * anfter thath,it will change new HSL to RGB for Text Color
     * you can read more about it in http://serennu.com/colour/rgbtohsl.php
     */

    /**
     * RGB to HSL
     *
     * @return void
     */
    private function RGB2HSL(){

        $red   = $this->Color_R / 255;
        $green = $this->Color_G /  255;
        $blue  = $this->Color_B /  255;

        $max = max($red,$green,$blue);
        $min = min($red,$green,$blue);
        $difference = $max - $min;
        $this->HSL_L = ($max + $min) / 2;

        if ($difference == 0){
            $this->HSL_H = 0;
            $this->HSL_S = 0;
        } else {
            if ($this->HSL_L < 0.5)
              $this->HSL_S = $difference / ($max + $min);
            else
              $this->HSL_S = $difference / (2 - $max - $min);

            $red_temp   = ((($max - $red)   / 6) + ($max / 2)) / $max;
            $green_temp = ((($max - $green) / 6) + ($max / 2)) / $max;
            $blue_temp  = ((($max - $blue)  / 6) + ($max / 2)) / $max;

            if ($red == $max)
                $this->HSL_H = $blue_temp - $green_temp;
            else if ($green == $max)
                $this->HSL_H = (1 / 3) + $red_temp - $blue_temp;
            else if ($blue == $max)
                $this->HSL_H = (2 / 3) + $green_temp - $red_temp;

            if ($this->HSL_H < 0)
                    $this->HSL_H += 1;
            if ($this->HSL_H > 1)
                    $this->HSL_H -=1;
        }
        $this->HSL_Complement();
    }

    /**
     * HSL complement
     *
     * @return void
     */
    private function HSL_Complement(){
        $this->HSL_H_complement = $this->HSL_H + 0.5;
        if ($this->HSL_H_complement > 1)
                $this->HSL_H_complement--;
    }

    /**
     * HSL to RGB
     *
     * @return void
     */
    private function HSL2RGB(){
        if ($this->HSL_S == 0){
            $this->TC_R = $this->HSL_L * 255;
            $this->TC_G = $this->HSL_L * 255;
            $this->TC_B = $this->HSL_L * 255;
        } else {
            if ($this->HSL_L < 0.5)
                    $var2 = $this->HSL_L * (1 + $this->HSL_S);
            else
                    $var2 = ($this->HSL_L + $this->HSL_S) - ($this->HSL_S * $this->HSL_L);

            $var1 = 2 * $this->HSL_L - $var2;
            $this->TC_R =  255 * $this->HUE2RGB($var1, $var2, $this->HSL_H_complement + (1 / 3));
            $this->TC_G =  255 * $this->HUE2RGB($var1, $var2, $this->HSL_H_complement);
            $this->TC_B =  255 * $this->HUE2RGB($var1, $var2, $this->HSL_H_complement - (1 / 3));
        }
    }
    
    /**
     * HUE to RGB
     *
     * @return void
     */
    private function HUE2RGB($var1,$var2,$varH){
        if ($varH < 0)
            $varH++;
        if ($varH > 1)
            $varH--;
        if ((6 * $varH) < 1)
            return ($var1 + ($var2 - $var1) * 6 * $varH);
        if ((2 * $varH) < 1)
            return ($var2);
        if ((3 * $varH) < 2)
            return ($var1 + ($var2 - $var1) * (2 / 3 - $varH) * 6);
        return ($var1);
    }

    //+ ---------------------- End of the XPart

    /**
     * Get image main RGB
     *
     * If you set ImageBackground,and dont set background color
     * it will be call,by these it can set background color
     * after that find text color to print on this image
     * these function check 64% of center of image,and get avrage of the RGB color
     *
     * @return void
     */
    private function getImageMainRGB(){

        switch ($this->ImageSuffix){
            case 'png':
                $im = @imagecreatefrompng($this->BackgroundImage);
                break;
            case 'jpg':
                $im = @imagecreatefromjpeg($this->BackgroundImage);
                break;
            case 'gif':
                $im = @imagecreatefromgif($this->BackgroundImage);
                break;
        }
        $x = imagesx($im);
        $y = imagesy($im);
        $x_start = round(($x / 10));
        $x_end   = round(9 * ($x / 10));
        $y_start = round(($y / 10));
        $y_end   = round(9 * ($y / 10));
        $sum = array('R' => 0 ,
                     'G' => 0 ,
                     'B' => 0);
        $count = round(($x * $y) / 10); // checking 10% of image (x_end - x_start) * (y_end - y_start) ,center of backgroundImage
        for ($i = 0; $i < $count; $i++){
            $rand_x = rand($x_start,$x_end);
            $rand_y = rand($y_start,$y_end);
            $rgb = imagecolorat($im, $rand_x, $rand_y);
            $sum['R'] += ($rgb >> 16) & 0xFF;
            $sum['G'] += ($rgb >> 8) & 0xFF;
            $sum['B'] += $rgb & 0xFF;
        }
        $this->Color_R = (int)($sum['R'] / $count);
        $this->Color_G = (int)($sum['G'] / $count);
        $this->Color_B = (int)($sum['B'] / $count);
    }

    /**
     * Random RGB
     *
     * Make random RGB code
     *
     * @return Integer
     */
    private function RandomRGB(){
        return (rand(0,255));
    }

    /**
     * RGB checker
     *
     * @param Integer $red (by reference)
     * @param Integer $green (by reference)
     * @param Integer $blue (by reference)
     *
     * @return void
     */
    private function RGBCheker(&$red, &$green, &$blue){
        //checking RGB color,if it's not true
        //it will be set by rand
        if (!($red >= 0 && $red <= 255))
            $red = $this->RandomRGB();
        if (!($green >= 0 && $green <= 255))
            $green = $this->RandomRGB();
        if (!($blue >= 0 && $green <= 255))
            $green = $this->RandomRGB();
    }

    /**
     * Get font source by random
     *
     * Make random font for imageftbox and imagefttext
     *
     * @return String
     */
    private function getFontSRC(){
        //
        return $this->fonts_dir."/".$this->font_can_be_user_src[rand(0,$this->font_can_be_use_count - 1)];
    }

    /**
     * Set using font source
     *
     * @return void
     */
    private function setFontCanBeUseSRC(){
        //making random fonts
        $count = count($this->fonts) - 1;
        for ($i = 0; $i < $this->font_can_be_use_count ; $i++){
            $rand = rand(0,$count);
            if (in_array($this->fonts[$rand], $this->font_can_be_user_src)){
                // Checking to dont have one font twice or more
                $i--;
                continue;;
            } else {
                $this->font_can_be_user_src[$i] = $this->fonts[$rand];
            }
        }
    }

    /**
     * Get font size and position
     *
     * @param String $font
     * @param String $char
     * @param Float $angle
     * @param Float $x
     *
     * @return Array
     */
    private function getFontSize_Position($font, $char, $angle, $x){
        // to set char in correct position with correct font size
        for ($i = 0;$i < 201;$i++){
            $pos = imageftbbox($i, $angle, $font, $char);
            if ($angle >= 0){
                if ((($pos[1] - $pos[5]) > $this->height || ($pos[2] - $pos[6] > $x)) || $i == 200){
                    $pos = imageftbbox(--$i, $angle, $font, $char);
                    $y = ($pos[1] - $pos[5]) + ($this->height - ($pos[1] - $pos[5]))/2 - 5;
                    return array('size' => $i,
                                 'YPos' => $y);
                }
            } else {
                if ((($pos[3] - $pos[7]) > $this->height || ($pos[4] - $pos[0] > $x)) || $i == 200){
                    $pos = imageftbbox(--$i, $angle, $font, $char);
                    $y = ($pos[3] - $pos[7]) + ($this->height - ($pos[3] - $pos[7]))/2 - ($pos[3] - $pos[1]) - 5;
                    return array('size' => $i,
                                 'YPos' => $y);
                }
            }
        }
    }

    /**
     * Get random angle
     *
     * @return Integer
     */
    private function randangle(){
        //return random angle 
        return (rand(-1 * $this->angle,$this->angle));
    }
    
    /**
     * Draw CAPTCHA
     *
     * Draw Image of the captcha code
     *
     * @return void
     */
    public function draw(){
        if ($this->BackgroundImage === null){
            if ($this->Color_B === null || $this->Color_B === null || $this->Color_B === null ){
                if ($this->TC_B === null || $this->TC_B === null || $this->TC_B === null)
                        $this->SetTextColor(0, 0, 0);
                //if obj doen't have background image,background color and text color,it will be set as 0, 0, 0
            } else {
                if ($this->TC_B === null || $this->TC_B === null || $this->TC_B === null){
                        $this->RGB2HSL();
                        $this->HSL2RGB();
                        //if obj doesn't have backgorund image,but have background color,and dont have textcolor,it will be set by XPart:D
                }
            }
        } else {
            if ($this->Color_B === null || $this->Color_B === null || $this->Color_B === null ){
                $this->getImageMainRGB(); // if obj doesn't background Image,but dont have background color,it will be set by this method
                if ($this->TC_B === null || $this->TC_B === null || $this->TC_B === null){
                        $this->RGB2HSL();
                        $this->HSL2RGB();
                        //if obj doesn't have TextColor ,it will be set by XPart:D
                }
            } else {
                if ($this->TC_B === null || $this->TC_B === null || $this->TC_B === null){
                        $this->RGB2HSL();
                        $this->HSL2RGB();
                        //XPart again,if TextColor doesnt set,but Background color set
                }
            }
        }
        // End of Set Background and Text Color
        $this->setFontCanBeUseSRC();//make random font
        
        if ($this->BackgroundImage != null){//have background
            //create image
            switch ($this->ImageSuffix){
                case 'gif':
                    $im = imagecreatefromgif($this->BackgroundImage);
                    break;
                case 'jpg':
                    $im = imagecreatefromjpeg($this->BackgroundImage);
                    break;
                case 'png':
                default:
                    $im = imagecreatefrompng($this->BackgroundImage);
            }
            $this->width = imagesx($im);
            $this->height= imagesy($im);
        } else {    //dont have background
            $im = imagecreatetruecolor($this->width, $this->height);
            if (!($this->Color_R === null || $this->Color_G === null || $this->Color_B === null)){
                $color_Back = imagecolorallocate($im, $this->Color_R , $this->Color_G, $this->Color_B);
                imagefilledrectangle($im, 0, 0, $this->width, $this->height, $color_Back);
            } else {
                //with out background (transparnet image)
                imagealphablending($im, false);
                $col=imagecolorallocatealpha($im,255,255,255,127);
                //Create overlapping 100x50 transparent layer
                imagefilledrectangle($im,0,0,$this->width, $this->height,$col);
                //Continue to keep layers transparent
                imagealphablending($im,true);
            }
        }
        $color_Text = imagecolorallocate($im, $this->TC_R , $this->TC_G, $this->TC_B);//make text color by RGB
        $count = strlen($this->Text);
        $XSize =   ($this->width / $count + 5);
        
        for ($i = 0; $i < $count; $i++){
            $char = substr($this->Text,$i,1);
            $font = $this->getFontSRC();
            $angle = $this->randangle();
            $array = $this->getFontSize_Position($font, $char, $angle,  $XSize);
            imagefttext($im, $array['size'], $angle,  $i * ($XSize - 5) + 5, $array['YPos'], $color_Text,$font, $char);
            //draw a char into image
        }        
        switch ($this->ImageSuffix){
        //send header and draw image
            case 'jpg':
                header('Content-type: image/jpeg');
                imagejpeg($im);
                break;
            case 'gif':
                header('Content-type: image/gif');
                imagegif($im);
                break;
            case 'png':
                header('Content-type: image/png');
                imagepng($im);
                break;
               default:
                imagesavealpha($im,true);
                imagepng($im,"MoeinCaptchaTranspartImage.png",1);
                header('Content-type: image/png');
                readfile("MoeinCaptchaTranspartImage.png");
                break;
        }
        //release a memory
        imagedestroy($im);
    }
}
?>