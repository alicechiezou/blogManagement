<?php

    class CategoryName{
        /**
         * カテゴリー名を表示
         * @param string $category  // 数字
         * @return string $result   // カテゴリ名
         */
        public static function setCategoryName($category){
            switch ($category){
            case '1':
                return 'ブログ';
                break;
            case '2':
                return '日常';
                break;
            default:
                return 'その他';
            }

        }

    }
?>