<?php

/**
 * 检测后台用户是否登录中间件
 */

namespace App\Http\Middleware;

use App\Components\AdminManager;
use App\Components\BusCompanyManager;
use App\Components\Common\ApiResponse;
use App\Components\Common\Utils;
use App\Components\CouponManager;
use App\Components\JobOrderItemManager;
use App\Components\JobOrderManager;
use App\Components\ManCompanyManager;
use App\Components\ProductManager;
use App\Components\ShopStoreManager;
use App\Components\SubProductManager;
use App\Components\UserManager;
use App\Models\Coupon;
use Closure;

class CheckItemValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $request->all();
        $pathInfo = $request->getPathInfo();
        //这里排除getListByCon的路由，以便确保有效性
        if ($data && strpos($pathInfo, 'getListByCon') == false) {
            //以下自由定义，确保传入id的信息的确值存在
            if (array_key_exists('user_id', $data) && !Utils::isObjNull($data['user_id'])) {
                $user = UserManager::getById($data['user_id']);
                if (!$user) {
                    return ApiResponse::makeResponse(false, "用户不存在", ApiResponse::INNER_ERROR);
                }
            }
        }
        return $next($request);
    }

}
