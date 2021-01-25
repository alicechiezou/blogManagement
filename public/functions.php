<?php

    /**
     * XSS対策（Webアプリケーションに対する代表的な攻撃）：エスケープ処理
     * 
     * @param string $str 対象の文字列
     * @return string 処理された文字列
     */
    function h($str){
        // $str …エスケープしたいもの、ENT_QUOTES…エスケープの内容
        return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
    }

    /**
     * CSRF対策
     * （捏造（ねつぞう）したHTTPリクエストを Webアプリケーションが受け付けないようにする）
     * @param void  // 引数なし
     * @return string csrf_token
     */
    function setToken(){

        // トークン生成
        $csrf_token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;

        return $csrf_token;
    }

?>