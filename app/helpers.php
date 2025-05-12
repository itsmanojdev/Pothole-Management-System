<?php

/***********************
 * To get route prepended with user role *
 **********************/

use App\PotholeStatus;

if (!function_exists("userRoute")) {
    function userRoute(string $route, ...$params)
    {
        return request()->user()->isAdmin() || request()->user()->isSuperAdmin()
            ? route("admin." . $route, implode(",", $params))
            : route("citizen." . $route, implode(",", $params));
    }
}

/***********************
 * To check route with user role *
 **********************/
if (!function_exists("userRouteIs")) {
    function userRouteIs(string $route)
    {
        return request()->user()->isAdmin() || request()->user()->isSuperAdmin()
            ? request()->routeIs("admin." . $route)
            : request()->routeIs("citizen." . $route);
    }
}

/***********************
 * To convert kebab/snake to title case (without - & _ ) *
 **********************/
if (!function_exists("titleCase")) {
    function titleCase(?string $str = "", string $case = "kebab")
    {
        switch ($case) {
            case 'kebab':
                return implode(" ", array_map(fn($ele) => Str::title($ele), explode("-", $str)));
            case 'snake':
                return implode(" ", array_map(fn($ele) => Str::title($ele), explode("_", $str)));
            default:
                return $str;
        }
    }
}

/***********************
 * If DB value is URL, return URL, else return asset path *
 **********************/
if (!function_exists("getImageSource")) {
    function getImageSource(string $image_path)
    {
        return filter_var($image_path, FILTER_VALIDATE_URL)
            ? $image_path
            : asset('storage/' . $image_path);
    }
}

/***********************
 * To check if pothole status is valid while updating status*
 **********************/
if (!function_exists("verifyStatus")) {
    function verifyStatus(PotholeStatus $currStatus, PotholeStatus $status)
    {
        $statusArr = PotholeStatus::cases();
        $flag = false;
        [$inValid, $valid] = Arr::partition(
            $statusArr,
            function ($status) use ($currStatus, &$flag) {
                if (!$flag && $status == $currStatus) {
                    $flag = true;
                }
                return !$flag;
            }
        );

        return in_array($status, $inValid)
            ? false
            : in_array($status, $valid);
    }
}
