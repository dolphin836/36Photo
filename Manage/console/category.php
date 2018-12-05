<?php

/**
 * 根据图片标签选择合适的分类
 */
class Category
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
            ['mark' => '户外', 'level' => 40],
            ['mark' => '悬崖', 'level' => 100],
            ['mark' => '树林', 'level' => 80],
            ['mark' => '火山', 'level' => 100],
            ['mark' => '岩石', 'level' => 80],
            ['mark' => '夜景', 'level' => 60],
            ['mark' => '沙漠', 'level' => 100],
            ['mark' => '雪山', 'level' => 100],
            ['mark' => '瀑布', 'level' => 100],
            ['mark' => '山谷', 'level' => 100],
            ['mark' => '草地', 'level' => 80],
            ['mark' => '公路', 'level' => 80],
            ['mark' => '洞穴', 'level' => 80],
            ['mark' => '小河', 'level' => 100],
            ['mark' => '码头', 'level' => 80],
            ['mark' => '烟花', 'level' => 100],
            ['mark' => '船', 'level' => 100],
            ['mark' => '防波提', 'level' => 100],
            ['mark' => '珊瑚礁', 'level' => 100],
            ['mark' => '吊桥', 'level' => 60],
            ['mark' => '喷泉', 'level' => 40]
        ],
        'animal' => [ // 动物
            ['mark' => '动物', 'level' => 100],
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
            ['mark' => '花', 'level' => 100],
            ['mark' => '树林', 'level' => 40]
        ],
        'build' => [ // 建筑
            ['mark' => '大厦', 'level' => 80],
            ['mark' => '街道', 'level' => 20],
            ['mark' => '街景', 'level' => 20],
            ['mark' => '建筑', 'level' => 100],
            ['mark' => '吊桥', 'level' => 80],
            ['mark' => '喷泉', 'level' => 80],
            ['mark' => '城堡', 'level' => 60],
            ['mark' => '谷仓', 'level' => 80],
            ['mark' => '车站', 'level' => 40],
            ['mark' => '宫殿', 'level' => 60],
            ['mark' => '监狱', 'level' => 60],
            ['mark' => '教堂', 'level' => 60],
            ['mark' => '圆顶', 'level' => 80],
            ['mark' => '楼梯', 'level' => 40],
            ['mark' => '祭坛', 'level' => 60],
            ['mark' => '演出', 'level' => 40],
            ['mark' => '夜景', 'level' => 40],
            ['mark' => '室内', 'level' => 60],
            ['mark' => '公路', 'level' => 60],
            ['mark' => '码头', 'level' => 60],
            ['mark' => '防波堤', 'level' => 60],
        ],
        'tech' => [ // 科技
            ['mark' => '笔记本电脑', 'level' => 80]
        ],
        'city' => [ // 城市
            ['mark' => '演出', 'level' => 40],
            ['mark' => '大厦', 'level' => 60],
            ['mark' => '街道', 'level' => 80],
            ['mark' => '饰品', 'level' => 20],
            ['mark' => '街景', 'level' => 80],
            ['mark' => '夜景', 'level' => 60],
            ['mark' => '公路', 'level' => 40],
            ['mark' => '喷泉', 'level' => 60],
            ['mark' => '小河', 'level' => 40],
            ['mark' => '码头', 'level' => 40],
            ['mark' => '烟花', 'level' => 60],
            ['mark' => '船', 'level' => 40],
            ['mark' => '城堡', 'level' => 20]
        ],
        'life' => [ // 生活
            ['mark' => '美女', 'level' => 40],
            ['mark' => '演出', 'level' => 40],
            ['mark' => '大厦', 'level' => 40],
            ['mark' => '沙滩', 'level' => 20],
            ['mark' => '街道', 'level' => 50],
            ['mark' => '户外', 'level' => 40],
            ['mark' => '饰品', 'level' => 60],
            ['mark' => '街景', 'level' => 20],
            ['mark' => '室内', 'level' => 40],
            ['mark' => '箱包', 'level' => 60],
            ['mark' => '女游泳衣', 'level' => 20],
            ['mark' => '草地', 'level' => 40],
            ['mark' => '家居物品', 'level' => 80],
            ['mark' => '老人', 'level' => 40],
            ['mark' => '衣物', 'level' => 80],
            ['mark' => '比基尼', 'level' => 40],
            ['mark' => '笔记本电脑', 'level' => 60],
            ['mark' => '烟花', 'level' => 80],
            ['mark' => '船', 'level' => 60],
            ['mark' => '黑板', 'level' => 60],
            ['mark' => '城堡', 'level' => 40],
            ['mark' => '相框', 'level' => 80],
            ['mark' => '谷仓', 'level' => 40]
        ],
        'design' => [ // 设计
            ['mark' => '饰品', 'level' => 20],
            ['mark' => '箱包', 'level' => 40],
            ['mark' => '家居物品', 'level' => 40],
            ['mark' => '衣物', 'level' => 60]
        ],
        'car' => [ // 汽车
            ['mark' => '街道', 'level' => 20],
            ['mark' => '汽车', 'level' => 100]
        ],
        'tour' => [ // 旅游
            ['mark' => '天空', 'level' => 20],
            ['mark' => '日出', 'level' => 40],
            ['mark' => '日落', 'level' => 40],
            ['mark' => '沙滩', 'level' => 20],
            ['mark' => '街道', 'level' => 20],
            ['mark' => '户外', 'level' => 60],
            ['mark' => '夜景', 'level' => 20],
            ['mark' => '箱包', 'level' => 50],
            ['mark' => '旅行', 'level' => 100]
        ],
        'sport' => [ // 运动
            ['mark' => '公路', 'level' => 20],
            ['mark' => '运动', 'level' => 100]
        ],
        'food' => [ // 食物
            ['mark' => '美食', 'level' => 100]
        ],
        'people' => [ // 人物
            ['mark' => '美女', 'level' => 100],
            ['mark' => '演出', 'level' => 40],
            ['mark' => '街道', 'level' => 10],
            ['mark' => '帅哥', 'level' => 100],
            ['mark' => '饰品', 'level' => 60],
            ['mark' => '女游泳衣', 'level' => 80],
            ['mark' => '老人', 'level' => 80],
            ['mark' => '比基尼', 'level' => 80]
        ],
        'fashion' => [ // 时尚
            ['mark' => '演出', 'level' => 30],
            ['mark' => '女游泳衣', 'level' => 40],
            ['mark' => '衣物', 'level' => 40],
            ['mark' => '比基尼', 'level' => 50]
        ],
        'girl' => [ // 少女
            ['mark' => '美女', 'level' => 60],
            ['mark' => '女游泳衣', 'level' => 30],
            ['mark' => '比基尼', 'level' => 30]
        ],
        'model' => [ // 模特
            ['mark' => '美女', 'level' => 40],
            ['mark' => '帅哥', 'level' => 40],
            ['mark' => '女游泳衣', 'level' => 20],
            ['mark' => '衣物', 'level' => 20],
            ['mark' => '比基尼', 'level' => 40]
        ],
        'art' => [ // 艺术
            ['mark' => '演出', 'level' => 60],
            ['mark' => '演出', 'level' => 80]
        ],
        'movie' => [ // 影视

        ],
        'dream' => [ // 创意

        ],
        'game' => [ // 游戏

        ],
        'superstar' => [ // 名人
            ['mark' => '美女', 'level' => 20],
        ]
    ];

    public static function set($mark = [])
    {
        $category = [];

        foreach (self::$map as $key => $item) {
            $category[$key] = 0;

            foreach ($item as $value) {
                if (in_array($value['mark'], $mark)) {
                    $category[$key] += $value['level'];
                }
            }
        }

        arsort($category, SORT_NUMERIC);

        return key($category);
    }
}

