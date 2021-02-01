<?PHP

    // 一度読み込みを行う
    require_once '../dbconnect.php';

    class csvprocessing
    {
    
        /**
         * ブログデータをCSV出力する
         * @param void
         * @rerurn $result
         */
        public static function csvdownLoad(){

            // ヘッダ出力
            $filename = 'blog.csv';
            if (!$fp = fopen($filename, 'w')) {
                echo "Cannot open file ($filename)";
                exit;
            }            

            // データベースからデータ取得
            $sql = 'SELECT * FROM blog';

            try{
                $pdo = connect();

                // ユーザからの入力をSQL文に含めて接続
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
           
                //　CSV文字列生成
                $csvstr = "";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $csvstr .= $row['title'] . ",";
                  $csvstr .= $row['content'] . ",";
                  $csvstr .= $row['category'] . ",";
                  $csvstr .= $row['updateTime'] . ",";
                  $csvstr .= $row['status'] . "\r\n";
                }
                
                fwrite($fp, "write sample");
                
                fclose($fp);
                header('Content-Type: text/csv');       // CSVを出力します
                header('Content-Disposition: attachment; filename='.$filename); // // $filename という名前で保存させます
                echo mb_convert_encoding($csvstr, "SJIS", "UTF-8"); //Shift-JISに変換したい場合のみ

                //close mysql
                $pdo = null;

                exit();

                return $blog_result;
            }catch(\Exception $e){
                return false;
            }
        }


        /**
         * ブログデータをCSVからアップロードする
         * @param string $filepath
         * @rerurn $result
         */
        public static function csvUpLoad($filepath){

            $result = false;
            
            $data = file_get_contents($filepath);       // ファイルの内容を全て文字列に読み込む
            $data = mb_convert_encoding($data, 'UTF-8', 'sjis-win');    // 文字化け対策：文字エンコーディングを変換する
            $temp = tmpfile();  // テンポラリファイルを作成

            fwrite($temp, $data);   // バイナリセーフなファイル書き込み処理
            rewind($temp);  // ファイルポインタの位置を先頭に戻す

            $sql = 'INSERT INTO blogs (title,content,category,updateTime,status) VALUES (?, ?, ?, ?, ?)';

            $pdo = connect();
            $stmt = $pdo->prepare($sql);

            /* トランザクション処理 */
            $pdo->beginTransaction();
            
            try {

                while ($row = fgetcsv($temp)) {     // ファイルポインタから行を取得し、CSVフィールドを処理                 
                    if ($row === array(null)) {
                        // 空行はスキップ
                        continue;
                    }
                    if (count($row) !== 5) {
                        // カラム数が異なる無効なフォーマット
                        throw new RuntimeException('Invalid column detected');
                    }

                   $executed = $stmt->execute($row);

                }
                if (!feof($temp)) {
                    // ファイルポインタが終端に達していなければエラー
                    throw new RuntimeException('CSV parsing error');
                }
                fclose($temp);
                $pdo->commit();
            } catch (Exception $e) {
                fclose($temp);
                $pdo->rollBack();
                throw $e;
            }
            
            return true;
        
        }


    }


?>