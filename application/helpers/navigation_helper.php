<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getBreadcrumb'))
{
    function getBreadcrumb($links=[])
    {
        $breadcrumb = '';

        if (!empty($links)) {

            $last = end($links);

            foreach ($links as $key => $link) {

                if($link == $last) {
                    $breadcrumb .= '<li class="breadcrumb-item active">'.$key.'</li>';
                }
                else{
                    $breadcrumb .= '<li class="breadcrumb-item"><a href="'.$link.'">'.$key.'</a></li>';
                }
            }
        }

        if (empty($breadcrumb)) {
            $breadcrumb .= '<li class="breadcrumb-item active">Utama</li>';
        }

        return $breadcrumb;
    }
}