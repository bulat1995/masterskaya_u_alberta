<?php
/**
* Трейт используемый в сущностях Act и Document 
*
*/

namespace App\Entity;

trait Price {
    
    public function getPriceText() {
        $n=floatval($this->getPrice());
        $words=array(
            900=>'девятьсот',800=>'восемьсот',700=>'семьсот',600=>'шестьсот',500=>'пятьсот',400=>'четыреста',300=>'триста',200=>'двести',100=>'сто',90=>'девяносто',80=>'восемьдесят',70=>'семьдесят',60=>'шестьдесят',50=>'пятьдесят',40=>'сорок',30=>'тридцать',20=>'двадцать',19=>'девятнадцать',18=>'восемнадцать',17=>'семнадцать',16=>'шестнадцать',15=>'пятнадцать',14=>'четырнадцать',13=>'тринадцать',12=>'двенадцать',11=>'одиннадцать',10=>'десять',9=>'девять',8=>'восемь',7=>'семь',6=>'шесть',5=>'пять',4=>'четыре',3=>'три',2=>'два',1=>'один',
        );
        $level=array(
            4=>array('миллиард', 'миллиарда', 'миллиардов'),
            3=>array('миллион', 'миллиона', 'миллионов'),
            2=>array('тысяча', 'тысячи', 'тысяч'),
        );
        list($rub,$kop)=explode('.',number_format($n,2));
        $kop=intval($kop);
        $parts=explode(',',$rub);
        for($str='', $l=count($parts), $i=0; $i<count($parts); $i++, $l--) {
            if (intval($num=$parts[$i])) {
                foreach($words as $key=>$value) {
                    if ($num>=$key) {
                        if ($l==2 && $key==1) {
                            $value='одна';
                        }
                        if ($l==2 && $key==2) {
                            $value='две';
                        }
                        $str.=($str!=''?' ':'').$value;
                        $num-=$key;
                    }
                }
                if (isset($level[$l])) {
                    $str.=' '.$this->num2word($parts[$i],$level[$l]);
                }
            }
        }
        if (intval($rub=str_replace(',','',$rub))) {
            $str.=' '.$this->num2word($rub,array('рубль', 'рубля', 'рублей'));
        }
        if($kop<10){
            $kop='0'.$kop;
        }
        $str.=($str!=''?' ':'').$kop;
        $str.=' '.$this->num2word(intval($kop),array('копейка', 'копейки', 'копеек'));
        return $str;
    }

    private function num2word($n,$words) {
        return ($words[($n=($n=$n%100)>19?($n%10):$n)==1?0 : (($n>1&&$n<=4)?1:2)]);
    }
}
