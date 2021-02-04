<?php
/**
 * this class allow to transcript number in letter
 * Author: Facinet KOUYATE
 */
class Formater
{
    
    /**
     * @var [array] units:the unit part of a number
     */
    private static $units;

    /**
     * @var [array] dozens: the dozens part of a number
     */
    private static $dozens;

    /**
     * @var [array] hundreds: the hundreds part of a number
     */
    private static $hundreds;

    /**
     * @var [array] thousands: the thousands part of a number
     */
    private static $thousands;

    /**
     * @var [array] millions: the millions part of a number
     */
    private static $millions;

    /**
     * @var [array] languages: the languages in which the number will transcript
     */
    private static $languages;
    
    
    /**
     * @return [array] units 
     */
    public static function dataUnits()
    {
        self::$units = [
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
        return self::$units;
    }

    /**
     * @return [array] dozens
     */
    public static function dataDozens()
    {
        self::$dozens = [
            'fr' => [
                '10' => 'dix', '11' => 'onze', '12' => 'douze', '13' => 'treize',
                '14' => 'quatorze', '15' => 'quinze', '16' => 'seize', '17' => 'dix-sept', 
                '18' => 'dix-huit', '19' => 'dix-neuf', '20' => 'vingt', '30' => 'trente',
                '40' => 'quarante', '50' => 'cinquante','60' => 'soixante', '70' => 'soixante-dix',
                '71' => 'soixante-onze', '72' => 'soixante-douze', '73' => 'soixante-treize',
                '74' => 'soixante-quatorze', '75' => 'soixante-quinze', '76' => 'soixante-seize',
                '80' => 'quatre-vingt', '90' => 'quatre-vingt-dix','91' => 'quatre-vingt-onze', 
                '92' => 'quatre-vingt-douze', '93' => 'quatre-vingt-treize','94' => 'quatre-vingt-quatorze', 
                '95' => 'quatre-vingt-quinze', '96' => 'quatre-vingt-seize',
            ],
            'en' => [
                '10' => 'ten', '11' => 'eleven', '12' => 'twelve', '13' => 'thirteen',
                '14' => 'fourteen', '15' => 'fifteen', '16' => 'sixteen','17' => 'seventeen', 
                '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty','30' => 'thirty', 
                '40' => 'fourty', '50' => 'fifty', '60' => 'sixty', '70' => 'seventy', 
                '80' => 'eighty', '90' => 'ninety',
            ]
        ];
        return self::$dozens;
    }

    /**
     * @return [array] hundreds
     */
    public static function dataHundreds()
    {
        // this array contains hundred
        self::$hundreds = [
            'fr' => ['100' => 'cent'],
            'en' => ['100' => 'hundred']
        ];
        return self::$hundreds;
    }

    /**
     * @return [array] languages
     */
    public static function dataLang()
    {
        self::$languages = ['fr', 'en'];
        return self::$languages;

    }

    /**
     * @return [array] millions
     */
    public static function dataMillions()
    {
        self::$millions = [
            'fr' => ['M' => 'millions'],
            'en' => ['M' => 'millions']
        ];
        return self::$millions;
    }

    /**
     * @return [array] thousands
     */
    public static function dataThousands()
    {
        self::$thousands = [
            'fr' => ['k' => 'milles'],
            'en' => ['k' => 'thousands']
        ]; 
        return self::$thousands;
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
                // verify if type number is not an integer, return an error message
                if((gettype($number) != 'integer')) return 'wrong, first argument must be an integer';
                
                $lang = strtolower($lang);
                
                if(in_array($lang, (array)self::dataLang()))
                {
                    //  verify if the number is simple unit, return his value in units array
                    //  else I proceed a treatment.
                    if($number <= 9)
                    {
                        return (self::dataUnits())["$lang"]["$number"];  
                    }
                    
                    // if number has dozen
                    elseif($number <= 99)
                    {
                        if(key_exists((string)$number, (self::dataDozens())["$lang"]))
                        {
                            return (self::dataDozens())["$lang"]["$number"];
                        }
                        else 
                        {
                            $unit = ($number % 10);
                            $dozen = 10 * (intdiv($number, 10));
                            return (self::dataDozens())["$lang"]["$dozen"].'-'.(self::dataUnits())["$lang"]["$unit"];
                        }
                    }

                    // if number has hundred
                    elseif ($number <= 999)
                    {
                        // verify if number has only an hundred
                        if($number % 100 == 0)
                        {
                            // verify if it's one hundred, return the value in hundreds else
                            // proceed a treatement
                            if(intdiv($number, 100)==1)
                            {
                                return $lang =='fr' ? (self::dataHundreds())["$lang"]["$number"] : 'one '.(self::dataHundreds())["$lang"]["$number"];
                            }
                            else
                            {
                                $numberOfhundred = (intdiv($number, 100));
                                return (self::dataUnits())["$lang"]["$numberOfhundred"].' '.(self::dataHundreds())["$lang"]['100'];
                               
                            }
                        }
                        // first, extract the hundred value in result1
                        // then,  do some recursivity for getting the value of other part of number
                        else
                        {
                            $unit = ($number % 10);
                            $numberOfhundred = (intdiv($number, 100));
                            $dozen = $number -($numberOfhundred * 100);
                            $result1 = (self::dataUnits())["$lang"]["$numberOfhundred"].' '.(self::dataHundreds())["$lang"]['100'];
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

        if(gettype($number) == 'integer')
        {
            // I verify the plage number and I proced treatment by case
            if($number < 1000) return self::toLetterNumber($number,$lang);

            elseif($number <= 999999)
            {
                $thousandNumber = (intdiv($number, 1000));
                $thousand = ($thousandNumber == 1) ? (($lang == 'fr')? 'mille': 'one thousand'): 
                    (self::toLetterNumber($thousandNumber, $lang).' '.(self::dataThousands())["$lang"]["k"]);
            
                if($number-($thousandNumber *1000) ==0)
                {
                    return $thousand;
                }
                $hundred = self::toLetterNumber($number - ($thousandNumber*1000),$lang);
                return $thousand.' '.$hundred;

            }

            elseif ($number <= 999999999)
            {
                $millionNumber = (intdiv($number, 1000000));
                $million = self::toLetterNumber($millionNumber, $lang).' '.(self::dataMillions())["$lang"]["M"];
            
                if($number-($millionNumber *1000000) == 0)
                {
                    return $million;
                }
                $thousand = self::toLetter(($number - ($millionNumber*1000000)),$lang);
                return $million.' '.$thousand; 
            }
            
            elseif($number > 999999999) return 'wrong, overflowing value';

        }
    }
}

// tests functions
echo Formater::toLetter(9005,'fr');
echo"\n";
echo Formater::toLetter(98889,'en');
echo"\n";

