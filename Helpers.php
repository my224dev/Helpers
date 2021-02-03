<?php
/**
 *  This is an class with a lot of useful methods in a projects
 *  all methods will be static, that's means you don't need to instaciate the class for use
 *  the methods.
 *  Author: Facinet KOUYATE
 */
class Helpers 
{
    
    /**
     * this function is a simple redirection
     * @param mixed $page
     * 
     * @return [void]
     */
    public static function redirect($page)
    {
        header('Location:'.$page);
        return ;
    }

    /** this function format the date in french 
     * @param Date $date
     * 
     * @return [Date]
     */
    public static function dateInFrench($date) {
        setlocale(LC_TIME,"fr_Fr");
        return strftime("%a %d %b %Y", strtotime(date('d-m-Y',strtotime($date))));
    }

    /**
     * this function display an number from 0 to 999 in letter number for exemple:
     * 1 should return un in french an one in english 
     * @param int number to transform in letter
     * @param string language
     * @return string 
     */
    public static function toLetterNumber($number, $lang)
    {
        
        if(!empty($lang) && (gettype($lang) == 'string'))
        {
            if($number == 0) return $lang == 'fr' ? 'zéro' : 'zero';
            else {
                // I verify if type numer is an integer else I return an error message
                if((gettype($number) != 'integer')) return 'wrong, first argument must be an integerl';
                $lang = strtolower($lang);
                // this array that contains the units
                $units = [
                    'fr' => [
                        '1' => 'un', '2' => 'deux', '3' => 'trois', '4' => 'quatre',
                        '5' => 'cinq', '6' => 'six', '7' => 'sept', '8' => 'huit',
                        '9' => 'neuf'
                    ],
                    'en' => [
                        '1' => 'one', '2' => 'two', '3' => 'tree', '4' => 'four',
                        '5' => 'five', '6' => 'six', '7' => 'seven', '8' => 'eight',
                        '9'=> 'nine'
                    ]
                ];

                // this array that contains the dozens
                $dozens = [
                    'fr' => [
                        '10' => 'dix', '11' => 'onze', '12' => 'douze', '13' => 'treize',
                        '14' => 'quatorze', '15' => 'quinze', '16' => 'seize','17' => 'dix-sept', 
                        '18' => 'dix-huit', '19' => 'dix-neuf', '20' => 'vingt','30' => 'trente', 
                        '40' => 'quarante', '50' => 'cinquante', '60' => 'soixante',
                        '70' => 'soixante-dix', '80' => 'quatre-vingt', '90' => 'quatre-vingt-dix',
                    ],
                    'en' => [
                        '10' => 'ten', '11' => 'eleven', '12' => 'twelve', '13' => 'thirteen',
                        '14' => 'fourteen', '15' => 'fifteen', '16' => 'sixteen','17' => 'seventeen', 
                        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty','30' => 'thirty', 
                        '40' => 'fourty', '50' => 'fifty', '60' => 'sixty', '70' => 'seventy', 
                        '80' => 'eighty', '90' => 'ninety',
                    ]
                ];
                // this array contains hundred
                $hundreds = [
                    'fr' => ['100' => 'cent'],
                    'en' => ['100' => 'hundred']
                ];
                // languages able
                $languages = ['fr', 'en'];
                
                if(in_array($lang, $languages))
                {
                    /*  verification if the number is simple unit, i return his value in units
                        else I proceed a treatment.
                    */
                    if($number >= 1 && $number <= 9)
                    {
                        return $units["$lang"]["$number"];  
                    }
                    // if number has dozen
                    else if($number >= 10 && $number <= 99)
                    {
                        if(key_exists((string)$number, $dozens["$lang"]))
                        {
                            return $dozens["$lang"]["$number"];
                        }
                        else 
                        {
                            $unit = ($number % 10);
                            $dozen = 10 * (intdiv($number, 10));
                            $result = $dozens["$lang"]["$dozen"].'-'.$units["$lang"]["$unit"];
                            return $result;
                        }
                    }
                    
                    // if number has hundred
                    else if ($number >= 99 && $number <= 999)
                    {
                        // verifying if number has only an hundred
                        if($number % 100 == 0)
                        {
                            // I verifu if it's one hundred, I return the value in hundreds else
                            // I proceed a treatement
                            if(intdiv($number, 100)==1)
                            {
                                return $lang =='fr' ? $hundreds["$lang"]["$number"] : 'one '.$hundreds["$lang"]["$number"];
                            }
                            else
                            {
                                $numberOfhundred = (intdiv($number, 100));
                                $result = $units["$lang"]["$numberOfhundred"].' '.$hundreds["$lang"]['100'];
                                return $result;
                            }
                        }
                        // first, I extract the hundred value in result1
                        // then, I do some recursivity for getting the value of other part of number
                        else
                        {
                            $unit = ($number % 10);
                            $numberOfhundred = (intdiv($number, 100));
                            $dozen = $number -($numberOfhundred * 100);
                            $result1 = $units["$lang"]["$numberOfhundred"].' '.$hundreds["$lang"]['100'];
                            $result2 = self::toLetterNumber($dozen,$lang);

                            return $result1.' '.$result2;
                        }
                        
                    }
                }
                
            }
        }
       return 'wrong, the second argument must be string (fr or en)';

    }
    /**
     * this function display an number from 0 to 999 999 999 in letter number for exemple:
     * 1 should return un in french an one in english 
     * @param int number to transform in letter
     * @param string language
     * @return string 
     */
    public static function toLetter($number, $lang)
    {
        // this array contains millions
        $millions = [
            'fr' => ['M' => 'millions'],
            'en' => ['M' => 'millions']
        ];
        // this array contains millions
        $thousands = [
            'fr' => ['k' => 'milles'],
            'en' => ['k' => 'thousands']
        ];
        // languages able
        $languages = ['fr', 'en'];

        if(gettype($number) == 'integer')
        {
            // I verify the plage number and I proced treatment by case
            if($number < 1000) return self::toLetterNumber($number,$lang);
            else if($number >= 1000 && $number <= 999999)
            {
                $thousandNumber = (intdiv($number, 1000));
                $thousand = ($thousandNumber == 1) ? (($lang == 'fr')? 'mille': 'one thousand'): 
                    (Helpers::toLetterNumber($thousandNumber, $lang).' '.$thousands["$lang"]["k"]);
            
                if($number-($thousandNumber *1000) ==0)
                {
                    return $thousand;
                }
                $hundred = Helpers::toLetterNumber($number - ($thousandNumber*1000),$lang);
                return $thousand.' '.$hundred;

            }
            else if ($number >= 1000000 && $number <= 999999999)
            {
                $millionNumber = (intdiv($number, 1000000));
                $million = self::toLetterNumber($millionNumber, $lang).' '.$millions["$lang"]["M"];
            
                if($number-($millionNumber *1000000) == 0)
                {
                    return $million;
                }
                $thousand = self::toLetter(($number - ($millionNumber*1000000)),$lang);
                return $million.' '.$thousand; 
            }
            else if($number > 999999999) return 'wrong, overflowing value';

        }
    }
}

// tests functions
echo Helpers::toLetter(999999999,'fr');
echo Helpers::toLetter(999999,'en');