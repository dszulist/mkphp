<?php
/**
 * MoneyOperations
 *
 * Zawiera f-cje do operacji na pieni�dzach
 *
 * @category	Mkphp
 * @package	MoneyOperations
 * @author      lwinnicki
 */
class MoneyOperations {

    /**
     * S�owny zapis kwot - tablica z wyrazami
     */
    private static $_words = array(
        'minus',
        array('zero', 'jeden', 'dwa', 'trzy', 'cztery', 'pi��', 'sze��', 'siedem', 'osiem', 'dziewi��'),
        array('dziesi��', 'jedena�cie', 'dwana�cie', 'trzyna�cie', 'czterna�cie', 'pi�tna�cie', 'szesna�cie', 'siedemna�cie', 'osiemna�cie', 'dziewi�tna�cie'),
        array('dziesi��', 'dwadzie�cia', 'trzydzie�ci', 'czterdzie�ci', 'pi��dziesi�t', 'sze��dziesi�t', 'siedemdziesi�t', 'osiemdziesi�t', 'dziewi��dziesi�t'),
        array('sto', 'dwie�cie', 'trzysta', 'czterysta', 'pi��set', 'sze��set', 'siedemset', 'osiemset', 'dziewi��set'),
        array('tysi�c', 'tysi�ce', 'tysi�cy'),
        array('milion', 'miliony', 'milion�w'),
        array('miliard', 'miliardy', 'miliard�w'),
        array('bilion', 'biliony', 'bilion�w'),
        array('biliard', 'biliardy', 'biliard�w'),
        array('trylion', 'tryliony', 'trylion�w'),
        array('tryliard', 'tryliardy', 'tryliard�w'),
        array('kwadrylion', 'kwadryliony', 'kwadrylion�w'),
        array('kwintylion', 'kwintyliony', 'kwintylion�w'),
        array('sekstylion', 'sekstyliony', 'sekstylion�w'),
        array('septylion', 'septyliony', 'septylion�w'),
        array('oktylion', 'oktyliony', 'oktylion�w'),
        array('nonylion', 'nonyliony', 'nonylion�w'),
        array('decylion', 'decyliony', 'decylion�w')
    );

    /**
     * Wylicza warto�� brutto
     *
     * @param float $netto		- warto�� netto
     * @param float $tax		- procentowa warto�� podatku
     * @param float $quantity	- ilo��
     * @return float
     */
    public static function calculateBrutto($netto, $tax, $quantity=1) {
        $netto = (float) $netto;
        $tax = (float) $tax;
        $quantity = (float) $quantity;

        $brutto = $quantity * $netto * ( 1 + ( $tax / 100 ) );

        return round($brutto, 2);
    }

    /**
     * Odmiana s�owa dla podanej liczby, np. ciastko/ciastka/ciastek
     *
     * Przyk�ad u�ycia:
     *  echo '16 '.MoneyOperations::varietyVerbal(array('punkt','punkty','punkt�w'), 16);
     *  // Wynik: "16 punkt�w"
     *  echo '103 '.MoneyOperations::varietyVerbal(array('ciastko','ciastka','ciastek'), 103);
     *  // Wynik: "103 ciastka"
     *
     * @param Array $wordsArray
     * @param Integer $number
     * @return String
     */
    public static function varietyVerbal($wordsArray, $number) {
        $txt = ($number == 1) ? $wordsArray[0] : $wordsArray[2];
        $unit = (int) substr($number, -1);
        $rest = $number % 100;
        if (($unit > 1 && $unit < 5) & !($rest > 10 && $rest < 20)) {
            $txt = $wordsArray[1];
        }
        return $txt;
    }

    /**
     * Odmiana warto�ci liczbowej trzycyfrowej (mniejszej ni� 1000) na jej s�own� posta�.
     * Wykorzystywane g��wnie w metodzie verbal()
     *
     * @param Integer $number
     * @return String
     */
    private static function _lessVariety($number) {
        $txt = '';

        $abs = abs((int) $number);
        if ($abs == 0) {
            return self::$_words[1][0];
        }

        $unit = $abs % 10;
        $tens = ($abs % 100 - $unit) / 10;
        $hundreds = ($abs - $tens * 10 - $unit) / 100;

        if ($hundreds > 0) {
            $txt .= self::$_words[4][$hundreds - 1] . ' ';
        }

        if ($tens > 0) {
            if ($tens == 1) {
                $txt .= self::$_words[2][$unit] . ' ';
            } else {
                $txt .= self::$_words[3][$tens - 1] . ' ';
            }
        }

        if ($unit > 0 && $tens != 1) {
            $txt .= self::$_words[1][$unit] . ' ';
        }

        return $txt;
    }

    /**
     * G��wna metoda zamieniaj�ca dowoln� liczb� na jej posta� s�own�.
     *
     * Przyk�ad u�ycia:
     *  echo MoneyOperations::verbal(103);
     *  // Wynik: "sto trzy"
     *  echo MoneyOperations::verbal('12345');
     *  // Wynik: "dwana�cie tysi�cy trzysta czterdzie�ci pi��"
     *  echo MoneyOperations::verbal('123456789');
     *  // Wynik: "sto dwadzie�cia trzy miliony czterysta pi��dziesi�t sze�� tysi�cy siedemset osiemdziesi�t dziewi��"
     *
     * @param Mixed $_number (zar�wno Integer jak i String)
     * @param Boolean $fractionNumeric - w przypadku wyst�pienia warto�ci po przecinku (grosze) wy�wietli podsumowanie
     *  numeryczne 'xx/100' (true) lub s�owne 'dwana�cie groszy' (false)
     * @return String
     */
    public static function verbal($number, $fractionNumeric=false) {
        $txt = '';

        $number = floatval($number);
        $tmpNumber = floor($number);
        $fraction = round($number - $tmpNumber, 2) * 100;

        if ($tmpNumber < 0) {
            $tmpNumber *= -1;
            $txt = self::$_words[0] . ' ';
        }

        if ($tmpNumber == 0) {
            $txt = self::$_words[1][0] . ' ';
        }

        settype($tmpNumber, 'string');
        $txtSplit = str_split(strrev($tmpNumber), 3);
        $txtSplitCount = count($txtSplit) - 1;

        for ($i = $txtSplitCount; $i >= 0; $i--) {
            $tmpNumber = (int) strrev($txtSplit[$i]);
            if ($tmpNumber > 0) {
                if ($i == 0) {
                    $txt .= self::_lessVariety($tmpNumber) . ' ';
                } else {
                    $txt .= $tmpNumber > 1 ? self::_lessVariety($tmpNumber) . ' ' : '';
                    $txt .= self::varietyVerbal(self::$_words[4 + $i], $tmpNumber) . ' ';
                }
            }
        }

        $txt .= self::varietyVerbal(array('z�oty', 'z�ote', 'z�otych'), $tmpNumber) . ' ';
        $txt .= 'i ' . ( $fractionNumeric ? $fraction . '/100 ' : self::_lessVariety($fraction) . ' ' ) . self::varietyVerbal(array('grosz', 'grosze', 'groszy'), $fraction) . ' ';

        return trim($txt);
    }

}