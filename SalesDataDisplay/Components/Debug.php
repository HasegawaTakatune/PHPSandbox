<?php

class Debug{
    
    static function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    static function debug_to_console_query($query) {
        echo '<script>console.log("Debug Query: ' . $query . '" );</script>';
    }

    static function debug_to_console_rows($data){
        $index = 0;
        $message = "";
        while($rows = $data->fetch(PDO::FETCH_ASSOC)){
            echo '<script>console.log("Debug Query: [[' . $index . ']]" );</script>';         
            foreach($rows as $key => $value){
                $message .= '[' . $key . ":" . $value . ']';
            }
            echo '<script>console.log("Debug Query: [' . $message . ']" );</script>';
            $index++;
            $message = "";
        }
    }
}

?>