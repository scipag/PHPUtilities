<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>    
    <title>shell_test.php</title>
</head>
<body>
    <div>
    <?php

        $cmd = "whoami";
        $cmdPath = "/usr/bin/whoami";
        echo "Test command: <em>$cmd</em><br><br>";

        //
        // system()
        //
        $methodName = "system()";
        ob_start();
        $output = system("$cmd 2>&1", $return);
        ob_end_clean();

        if(strlen($output) > 0 && $return == 0) {
            echo "[*] $methodName works. Output value: <b>" . $output . "</b><br>";
        }
        else {
            echo "[!] $methodName fails. Return value: <b>" . $return . "</b><br>";    
        }

        //
        // exec();
        //
        $methodName = "exec()"; $output = ""; $return = "";
        exec($cmd, $output, $return);
        if(strlen($output[0]) > 0 && $return == 0) {
            echo "[*] $methodName works. Output value: <b>" . $output[0] . "</b><br>";
        }
        else {
            echo "[!] $methodName fails. Return value: <b>" . $return . "</b><br>";    
        }

        //
        // shell_exec()
        //
        $methodName = "shell_exec()"; $output = ""; $return = "";
        $output = shell_exec($cmd);
        if(strlen($output[0]) > 0) {
            echo "[*] $methodName works. Output value: <b>" . $output . "</b><br>";
        }
        else {
            echo "[!] $methodName fails.<br>";    
        }

        //
        // backticks
        //
        $methodName = "backticks"; $output = ""; $return = "";
        $output = `$cmd`;
        if(strlen($output[0]) > 0) {
            echo "[*] $methodName works. Output value: <b>" . $output . "</b><br>";
        }
        else {
            echo "[!] $methodName fails.<br>";    
        }

        //
        // passthru()
        //
        $methodName = "passthru()"; $output = ""; $return = "";
        ob_start();
        passthru($cmd, $return);
        $output = ob_get_contents();
        ob_end_clean();
        if(strlen($output[0]) > 0 && $return == 0) {
            echo "[*] $methodName works. Output value: <b>" . $output . "</b><br>";
        }
        else {
            echo "[!] $methodName fails.<br>";    
        }

        //
        // popen()
        //
        $methodName = "popen()"; $output = ""; $return = "";        
        $handle = popen($cmdPath, "r");
        $output = fread($handle, 2096);
        pclose($handle);
        if(strlen($output) > 0) {
            echo "[*] $methodName works. Output value: <b>" . $output . "</b><br>";
        }
        else {
            echo "[!] $methodName fails.<br>";    
        }        

        //
        // proc_open()
        //
        $methodName = "proc_open()"; $output = ""; $return = "";   
        $descriptorspec = array(
           0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
           1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
           2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
        );

        $cwd = '/tmp';
        $env = array('some_option' => 'aeiou');

        $process = proc_open($cmd, $descriptorspec, $pipes, $cwd, $env);
        if (is_resource($process)) {          
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);            
            $return = proc_close($process);
        }        
        if(strlen($output) > 0 && $return == 0) {
            echo "[*] $methodName works. Output value: <b>" . $output . "</b><br>";
        }
        else {
            echo "[!] $methodName fails.<br>";    
        }

        //
        // pcntl_exec()
        //
        $methodName = "pcntl_exec()"; $output = ""; $return = "";        
        if (!function_exists("pcntl_exec")) {
            echo "[!] $methodName doesn't exist<br>";
        }
        else {
            echo "[*] $methodName exists, but no output/return possible<br>";
        }
    ?>
    </div>
</body>
</html>