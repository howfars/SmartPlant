<?php
/**
 * Created by PhpStorm.
 * User: CHY
 * Date: 2017/9/9
 * Time: 15:40
 */

        function connectDB(){
            $conn = new mysqli('localhost','root','password');
            if (!$conn) {
                die("failed");
            }
            $conn->query("SET NAMES UTF8");
            $conn->select_db('StationTest') or die("connot find");
            //@mysql_query("SET NAMES UTF8");
            //@mysql_select_db('weathertest', $conn) or die("connot find");
            return $conn;
        }

        function reverse($array){
            $size = count($array);

            for($i=0;$i<=floor($size/2);$i++){
                $b = $array[$size-$i-1];
                $array[$size-$i-1] = $array[$i];
                $array[$i] = $b;
            }
            return $array;

        }

        $conn = connectDB();

        $result = $conn->query('SELECT Temperature,Humidity,Light,Moisture FROM weathertest ORDER BY id DESC LIMIT 0,1');

        // $result_hum = $conn->query('SELECT Humidity FROM weathertest ORDER BY id DESC LIMIT 0,1');

        /*$result_tem_arr = array();   //存储数组
        while ($rows = mysqli_fetch_array($result_tem)) {      //$rows是数组
            settype($rows['Temperature'], 'float');          //string变成float
            $result_tem_arr[] = $rows['Temperature'];        //提取赋值
        }
        //$result_tem_arr = reverse($result_tem_arr);
        $json_tem = json_encode($result_tem_arr);       //转化为json格式

        $result_hum_arr = array();   //存储数组
        while ($rows = mysqli_fetch_array($result_hum)) {      //$rows是数组
        settype($rows['Humidity'], 'float');          //string变成float
        $result_hum_arr[] = $rows['Humidity'];        //提取赋值
        }
        //$result_tem_arr = reverse($result_tem_arr);
        $json_hum = json_encode($result_hum_arr);      //转化为json格式
        */

        $result_arr=array();
        if ($rows=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
            settype($rows['Temperature'], 'float');
            settype($rows['Humidity'], 'float');
            settype($rows['Light'], 'float');
            settype($rows['Moisture'], 'float');
        }
        $json_res=json_encode($rows);
        echo $json_res;


        ?>