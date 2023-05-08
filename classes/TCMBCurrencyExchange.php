<?php
class currency {
    private $date;
    private $url;
    private $xml;
    private $currencies;
    private $cur;
    private $apikey;
    private $EUR_TO_USD;
    private $USD_TO_EUR;
    private $EUR_TO_TL;
    private $TL_TO_EUR;
    private $USD_TO_TL;
    private $TL_TO_USD;

    public function __construct($apikey) {
        $this->apikey = $apikey;
        $this->EUR_TO_USD = "e2u";
        $this->USD_TO_EUR = "u2e";
        $this->EUR_TO_TL = "e2t";
        $this->TL_TO_EUR = "t2e";
        $this->USD_TO_TL = "u2t";
        $this->TL_TO_USD = "t2u";
    }

    public function isWeekend($date) {
        if ((date('N', strtotime($date)) >= 6) == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function checkDay($date) {
        if ($this->isWeekend($date)) {
            if (date('D', strtotime($date)) == "Sat") {
                return 1;
            } 
            elseif (date('D', strtotime($date)) == "Sun") {
                return 2;
            }
            else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function convert($amount,$method,$date) {
        $date = date('d-m-Y', strtotime($date));
        if ($this->checkDay($date) == 1) {
            $newdate = date('d-m-Y', strtotime('-1 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series=TP.DK.USD.A-TP.DK.USD.S-TP.DK.EUR.A-TP.DK.EUR.S-TP.DK.EUR.C-TP.DK.USD.C&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        }
        elseif ($this->checkDay($date) == 2) {
            $newdate = date('d-m-Y', strtotime('-2 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series=TP.DK.USD.A-TP.DK.USD.S-TP.DK.EUR.A-TP.DK.EUR.S-TP.DK.EUR.C-TP.DK.USD.C&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } else {
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series=TP.DK.USD.A-TP.DK.USD.S-TP.DK.EUR.A-TP.DK.EUR.S-TP.DK.EUR.C-TP.DK.USD.C&startDate='.$date.'&endDate='.$date.'&type=xml&key='.$this->apikey.'';
        }

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_URL, $this->url );
        
        $this->xml = curl_exec($curl);
        
        $this->currencies = new SimpleXMLElement($this->xml);
        $this->cur = $this->currencies->items;
        
        switch ($method) {
            case $this->EUR_TO_USD:
                # EUR TO USD Convertation
                $calc = $amount * $this->cur->TP_DK_EUR_C;
                return $calc;
                break;

            case $this->USD_TO_EUR:
                # USD TO EUR Convertation
                $usd_to_eur = 2 - $this->cur->TP_DK_EUR_C;
                $calc = $amount * $usd_to_eur;
                return $calc;
                break;

            case $this->TL_TO_USD:
                # TL TO USD Convertation
                $calc = $amount * $this->cur->TP_DK_USD_S;
                return $calc;
                break;

            case $this->USD_TO_TL:
                # USD TO TL Convertation
                $calc = $amount * $this->cur->TP_DK_USD_A;
                return $calc;
                break;

            case $this->TL_TO_EUR:
                # TL TO EUR Convertation
                $calc = $amount * $this->cur->TP_DK_EUR_S;
                return $calc;
                break;

            case $this->EUR_TO_TL:
                # EUR TO TL Convertation
                $calc = $amount * $this->cur->TP_DK_EUR_A;
                return $calc;
                break;
            
            default:
                "Wrong defination please set it true";
                break;
        }
    }




    public function exchange($Tcurrency, $method, $amount, $date) {
        $Tcurrency = strtoupper($Tcurrency);
        $method = strtoupper($method);
        $series_stmt = "TP.DK.".$Tcurrency.".".$method."";

        $date = date('d-m-Y', strtotime($date));
        if ($this->checkDay($date) == 1) { ////Tarih eğer hafta cumartesi gününe denk geliyorsa cuma gününü baz alıyor
            $newdate = date('d-m-Y', strtotime('-1 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } 
        elseif ($this->checkDay($date) == 2) { //Tarih eğer hafta pazar gününe denk geliyorsa cuma gününü baz alıyor
            $newdate = date('d-m-Y', strtotime('-2 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } 
        elseif (date('d-m', strtotime($date)) == "30-08") { // Eğer tarih zafer bayarmına denk geliyorsa bir gün önceki kuru alıyor
            $newdate = date('d-m-Y', strtotime('-1 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } 
        elseif (date('d-m', strtotime($date)) == "01-01") { // Eğer tarih zafer yılbaşına denk geliyorsa bir gün önceki kuru alıyor
            $newdate = date('d-m-Y', strtotime('-1 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } 
        elseif (date('d-m', strtotime($date)) == "01-05") { // Eğer tarih zafer bayramına denk geliyorsa bir gün önceki kuru alıyor
            $newdate = date('d-m-Y', strtotime('-1 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } 
        elseif (date('d-m', strtotime($date)) == "19-05") {
            $newdate = date('d-m-Y', strtotime('-1 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } 
        elseif (date('d-m', strtotime($date)) == "19-05") {
            $newdate = date('d-m-Y', strtotime('-1 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } 
        elseif (date('d-m', strtotime($date)) == "15-07") {
            $newdate = date('d-m-Y', strtotime('-1 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } 
        elseif (date('d-m', strtotime($date)) == "29-10") {
            $newdate = date('d-m-Y', strtotime('-1 day', strtotime($date)));
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$newdate.'&endDate='.$newdate.'&type=xml&key='.$this->apikey.'';
        } 
        else {
            $this->url = 'https://evds2.tcmb.gov.tr/service/evds/series='.$series_stmt.'&startDate='.$date.'&endDate='.$date.'&type=xml&key='.$this->apikey.'';
        }

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_URL, $this->url );
        
        $this->xml = curl_exec($curl);
        
        $this->currencies = new SimpleXMLElement($this->xml);
        $this->cur = $this->currencies->items;
        $series_stmt = str_replace('.','_',$series_stmt);
        return $this->cur->$series_stmt;
    }

}
?>