<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/12/4
 * Time: 9:23
 */

namespace App\Components\Common;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Lvht\GeoHash;

class GeoHashTool
{
    /**
     * 经纬度转GeoHash编码
     *
     * $lat  $lon经纬度参数
     *
     * $geo是geohash后的散列值
     *
     */
    public static function getGeoHash($lat, $lon, $distance = null)
    {
        $geo = GeoHash::encode($lon, $lat);
        return $geo;
    }

    /*
     * 获取某一经度周边的geohashgeo数组
     *
     * By TerryQi
     *
     * @param lat、lon经纬度，distance：获取精度默认为6
     *
     * @return 返回精度范围内的geohash数组
     *
     * 在数据库中的用法如下
     *
     * 在Manager的getListByCon方法中
     * 
        //geohash检索，需要传入geohash和distance
        if (array_key_exists('lat', $con_arr) && !Utils::isObjNull($con_arr['lat'])
            && array_key_exists('lon', $con_arr) && !Utils::isObjNull($con_arr['lon'])
            && array_key_exists('distance', $con_arr) && !Utils::isObjNull($con_arr['distance'])) {
            $neighbors_arr = Utils::geoNeighbors($con_arr['lat'], $con_arr['lon'], $con_arr['distance']);
            Utils::processLog(__METHOD__, "", "neighbors_arr:" . json_encode($neighbors_arr));
            $infos = $infos->wherein(DB::raw('LEFT(`geohash`,' . "{$con_arr['distance']}" . ')'), $neighbors_arr);
        }
     */
    public static function geoNeighbors($lat, $lon, $distance = 6)
    {
        $geohash = self::getGeoHash($lat, $lon);
        $m_geohash = substr($geohash, 0, $distance);
        $neighbor_geohashs_arr = GeoHash::expand($m_geohash);
        for ($i = 0; $i < sizeof($neighbor_geohashs_arr); $i++) {
            //因为默认$neighbor_geohashs_arr的形式如下
            //["wxrwn7zzzzz","wxrwp7zzzzz","wxry07zzzzz","wxrty7zzzzz","wxrvb7zzzzz","wxrtw7zzzzz","wxrtx7zzzzz","wxrv87zzzzz","wxrtz"]
            //应该根据distance进行剪裁字符串，以便于后续的数据库匹配
            $neighbor_geohashs_arr[$i] = substr($neighbor_geohashs_arr[$i], 0, $distance);
        }
        array_push($neighbor_geohashs_arr, $m_geohash);
        return $neighbor_geohashs_arr;
    }

}