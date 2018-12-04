<?php

/**
 * 根据图片标签选择合适的分类
 */
class Categroy
{
    // 标签与分类的映射关系
    private static $map = [
        'default' => [ // 默认
            ['mark' => '其他', 'level' => 100],
        ],
        'nature' => [ // 大自然
            ['mark' => '天空', 'level' => 100],
            ['mark' => '日出', 'level' => 100],
            ['mark' => '日落', 'level' => 100],
            ['mark' => '湖', 'level' => 100],
            ['mark' => '山峰', 'level' => 100],
            ['mark' => '海洋', 'level' => 100],
            ['mark' => '海滨', 'level' => 100],
            ['mark' => '迷雾', 'level' => 100],
            ['mark' => '沙滩', 'level' => 100],
            ['mark' => '户外', 'level' => 100],
            ['mark' => '悬崖', 'level' => 100],
            ['mark' => '树林', 'level' => 100],
            ['mark' => '火山', 'level' => 100],
            ['mark' => '岩石', 'level' => 100],
            ['mark' => '夜景', 'level' => 100],
            ['mark' => '沙漠', 'level' => 100],
            ['mark' => '雪山', 'level' => 100],
            ['mark' => '瀑布', 'level' => 100],
            ['mark' => '山谷', 'level' => 100],
            ['mark' => '草地', 'level' => 100],
            ['mark' => '公路', 'level' => 100],
            ['mark' => '洞穴', 'level' => 100],
            ['mark' => '小河', 'level' => 100],
            ['mark' => '码头', 'level' => 100],
            ['mark' => '烟花', 'level' => 100],
            ['mark' => '船', 'level' => 100],
            ['mark' => '防波提', 'level' => 100],
            ['mark' => '珊瑚礁', 'level' => 100]
        ],
        'animal' => [ // 动物
            ['mark' => '水母', 'level' => 100],
            ['mark' => '鸟', 'level' => 100],
            ['mark' => '鱼', 'level' => 100],
            ['mark' => '猫', 'level' => 100],
            ['mark' => '海龟', 'level' => 100],
            ['mark' => '灰鲸', 'level' => 100],
            ['mark' => '公牛', 'level' => 100],
            ['mark' => '狮子', 'level' => 100],
            ['mark' => '豹', 'level' => 100],
            ['mark' => '虎鲸', 'level' => 100],
            ['mark' => '瓢虫', 'level' => 100],
            ['mark' => '山羊', 'level' => 100],
            ['mark' => '乌林鸮', 'level' => 100],
            ['mark' => '马', 'level' => 100],
            ['mark' => '老虎', 'level' => 100],
            ['mark' => '水蛇', 'level' => 100],
            ['mark' => '阿拉伯骆驼', 'level' => 100],
            ['mark' => '非洲猎豹', 'level' => 100],
            ['mark' => '大猩猩', 'level' => 100],
            ['mark' => '皱褶蜥蜴', 'level' => 100],
            ['mark' => '美国短吻鳄', 'level' => 100],
            ['mark' => '非洲猎豹', 'level' => 100],
            ['mark' => '爱尔兰水猎犬', 'level' => 100],
            ['mark' => '郊狼', 'level' => 100],
            ['mark' => '北极熊', 'level' => 100],
            ['mark' => '爱斯基摩犬', 'level' => 100],
            ['mark' => '红狐狸', 'level' => 100],
            ['mark' => '大白鲨', 'level' => 100],
            ['mark' => '非洲变色龙', 'level' => 100]
        ],
        'plant' => [ // 植物 
            ['mark' => '植物', 'level' => 100],
            ['mark' => '花', 'level' => 100]
        ],
        'build' => [ // 建筑
            ['mark' => '大厦', 'level' => 80],
            ['mark' => '街道', 'level' => 20],
            ['mark' => '街景', 'level' => 20],
            ['mark' => '建筑', 'level' => 100],
            ['mark' => '吊桥', 'level' => 60],
            ['mark' => '喷泉', 'level' => 60],
            ['mark' => '喷泉', 'level' => 20],
            ['mark' => '城堡', 'level' => 60],
            ['mark' => '谷仓', 'level' => 60],
            ['mark' => '车站', 'level' => 40],
            ['mark' => '宫殿', 'level' => 60],
            ['mark' => '监狱', 'level' => 60],
            ['mark' => '教堂', 'level' => 60],
            ['mark' => '圆顶', 'level' => 80],
            ['mark' => '楼梯', 'level' => 40],
            ['mark' => '祭坛', 'level' => 60]
        ],
        'tech' => [ // 科技

        ],
        'city' => [ // 城市

        ],
        'life' => [ // 生活

        ],
        'design' => [ // 设计

        ],
        'car' => [ // 汽车

        ],
        'tour' => [ // 旅游

        ],
        'sport' => [ // 运动

        ],
        'food' => [ // 食物

        ],
        'people' => [ // 人物

        ],
        'fashion' => [ // 时尚

        ],
        'girl' => [ // 少女

        ],
        'model' => [ // 模特

        ],
        'art' => [ // 艺术

        ],
        'movie' => [ // 影视

        ],
        'dream' => [ // 创意

        ],
        'game' => [ // 游戏

        ],
        'superstar' => [ // 名人

        ]
    ];

    public static function set($mark = [])
    {
        $categroy = [];

        foreach (self::$map as $key => $item) {
            $categroy[$key] = 0;

            foreach ($item as $value) {
                if (in_array($value['mark'], $mark)) {
                    $categroy[$key] += $value['level'];
                }
            }
        }

        arsort($categroy, SORT_NUMERIC);

        return key($categroy);
    }
}

