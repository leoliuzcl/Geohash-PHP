<?php

class GeoHash {
    private $coding = '0123456789bcdefghjkmnpqrstuvwxyz';

    private $codingMap = [];
    private $long = [-180, 180];
    private $lat = [-90.0, 90.0];
    public function encode($lat, $lng, $deep) {
        // if ($deep < 5) {
        //     $deep = 5;
        // }
        $bitLen = $deep * 5;
        $bitCount = 0;
        $bit = 0;
        $flag = 1;
        $num = 0;
        $geoHash = '';
        while ($bitCount++ < $bitLen) {
            if ($flag) {
                $midLng = ($this->long[0] + $this->long[1]) / 2;
                if ($lng > $midLng) {
                    $num += pow(2, 4 - $bit);
                    $this->long[0] = $midLng;
                } else {
                    $this->long[1] = $midLng;
                }
            } else {
                $midLat = ($this->lat[0] + $this->lat[1]) / 2;
                if ($lat > $midLat) {
                    echo 1;
                    $num += pow(2, 4 - $bit);
                    $this->lat[0] = $midLat;
                } else {
                    echo 0;
                    $this->lat[1] = $midLat;
                }
            }
            $flag = !$flag;
            if ($bitCount%5 == 0) {
                $geoHash .= $this->coding[$num];
                $bit = 0;
                $num = 0;
            } else {
                $bit++;
            }
        }
        return $geoHash;
    }

    public function decode($hash) {
        if (empty(($hash))) {
            return '';
        }
        $hash = strtolower($hash);
        $hashLen = strlen($hash);
        $flag = 1;
        for ($i=0; $i < $hashLen; $i++) {
            $pos = strpos($this->coding, $hash[$i]);
            $binCode = str_pad(decbin($pos), 5, 0 ,STR_PAD_LEFT);
            for($j=0; $j < 5; $j++) {
                if ($flag) {
                    $this->long[!$binCode[$j]] = ($this->long[0] + $this->long[1]) / 2;
                } else {
                    $this->lat[!$binCode[$j]] = ($this->lat[0] + $this->lat[1]) / 2;
                }
                $flag = !$flag;
            }
        }
        return [
            'long' => ($this->long[0] + $this->long[1]) / 2,
            'lat' => ($this->lat[0] + $this->lat[1]) / 2
        ];
    }
}
//wm3yr31d2524
//wm3yr31d2524

$a = new Geohash();
echo $a->encode(22.546274, 40.939518, 4);
echo PHP_EOL;
$b = $a->decode('wm3yr31d2524');
var_dump($b);