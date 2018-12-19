<?php

namespace Dolphin\Ting\Librarie;

use JasonGrimes\Paginator;
// 分页插件
class Page
{
    public static function reder($url, $total, $page, $count, $query)
    {
        $paginator = new Paginator($total, $count, $page, $url . '?page=(:num)');

        $html = "";

        if ($paginator->getNumPages() > 1) {
            $html .= "<nav aria-label='Page navigation'>";
            $html .= "<ul class='pagination pagination-seperated justify-content-end'>";
            // 上一页
            $class = '';

            if (! $paginator->getPrevUrl()) {
                $class = 'disabled';
            }

            $html .= "<li class='page-item " . $class . "'><a class='page-link' href='" . $paginator->getPrevUrl() . $query . "'>前</a></li>";
            // 中间页
            foreach ($paginator->getPages() as $p) {
                if ($p['url']) {
                    $class = '';

                    if ($p['isCurrent']) {
                        $class = 'active';
                    }

                    $html .= "<li class='page-item " . $class . "'><a class='page-link' href='" . $p['url'] . $query . "'>" . $p['num'] . "</a></li>";
                } else {
                    $html .= "<li class='page-item disabled'><a class='page-link' href='javascript:void(0)'>" . $p['num'] . "</a></li>";
                }
            }
            // 下一页
            $class = '';

            if (! $paginator->getNextUrl()) {
                $class = 'disabled';
            }

            $html .= "<li class='page-item " . $class . "'><a class='page-link' href='" . $paginator->getNextUrl() . $query . "'>后</a></li>";
        
            $html .= "</ul></nav>";
        }

        return [
            'text' => '共 ' . $total . ' 条记录，总计 ' . ceil($total / $count) . ' 页，当前位于第 ' . $page . ' 页',
            'link' => $html
        ];
    }
}

