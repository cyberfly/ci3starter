<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applogger
{
    private $_store_in;
    private $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->config('applogger', TRUE);
        $this->_store_in = $this->CI->config->item('store_in', 'applogger');
        $this->_set_directory();
        $this->_verify_settings();
    }

    /*
     * log something
     */
    public function log($message, $code = 0, $user_id = 0)
    {
        $session_user_id = $this->CI->config->item('session_user_id','applogger');
        if(($user_id==0) && !empty($session_user_id))
        {
            $user_id = isset($_SESSION[$session_user_id]) ? $_SESSION[$session_user_id] : '0';
        }

        if($this->_set_message($message,$user_id,$code))
        {
            return TRUE;
        }
        else
        {
            show_error('Something wrong with Applogger Library');
        }
        return FALSE;
    }

    /*
     * delete logs
     */

    public function delete_log($user_id = NULL, $date = NULL)
    {
        if($this->_delete_logs($user_id, $date))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /*
     * retrieve something
     */
    public function get_log($user_id = NULL, $code = NULL, $date = NULL, $order_by = NULL, $limit = NULL)
    {
        return $this->_get_messages($user_id, $code, $date, $order_by, $limit);
    }



    private function _set_message($message,$user_id,$code)
    {
        if($this->_store_in == 'database')
        {
            $date_time = date('Y-m-d H:i:s');
            $insert_data = array(
                'no_kp' => $user_id,
                'masa' => $date_time,
                'kod' => $code,
                'keterangan' => $message
            );
            if($this->CI->mapplogger->set_message($insert_data))
            {
                return TRUE;
            }
        }
        else {
            $date = date('Y-m-d');
            $date_time = date('Y-m-d H:i:s');
            $file = $this->_store_in.'/log-' . $user_id . '-' . $date . '.php';
            $log_message = $date_time . ' *-* ' . $code . ' *-* ' . $message . "\r\n";
            if (!file_exists($file)) {
                // File doesn't exists so we need to first write it.
                $log_message = "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\r\n\r\n" . $log_message;
            }
            $log = fopen($file, "a");
            if (fwrite($log, $log_message))
            {
                fclose($log);
                return TRUE;
            }
            else
            {
                show_error('Couldn\'t write on the file');
            }
        }
        return FALSE;
    }

    private function _get_messages($user_id = NULL, $code = NULL, $date = NULL, $order_by = NULL, $limit = NULL)
    {
        if($this->_store_in == 'database')
        {
            $where = array();
            if(isset($user_id)) $where['no_kp'] = $user_id;
            if(isset($code)) $where['kod'] = $code;
            if(isset($date))
            {
                $where['masa >='] = $date.' 00:00:00';
                $where['masa <='] = $date.' 23:59:59';
            }
            if(!isset($order_by)) $order_by = 'masa DESC';
            return $this->CI->mapplogger->get_messages($where, $order_by, $limit);
        }
        else
        {
            $user_id = (isset($user_id)) ? $user_id : '*';
            $date = (isset($date)) ? $date : '*';
            $files = $this->_store_in.'/log-' . $user_id . '-' . $date . '.php';
            $messages = array();
            foreach (glob($files) as $filename)
            {
                $log = file_get_contents($filename);
                $lines = explode("\r\n",$log);
                for ($k=2; $k<count($lines); $k++) {
                    if(strlen($lines[$k])>0)
                    {
                        $line = explode('*-*',$lines[$k]);
                        $date_time = $line[0];
                        $code = $line[1];
                        $message = $line[2];
                        $messages[] = array('no_kp'=>$user_id,'masa'=>$date_time,'kod'=>$code,'keterangan'=>$message);
                    }
                }
            }
            return json_decode(json_encode($messages));
        }
    }

    private function _delete_logs($user_id = NULL,$date = NULL)
    {
        $where = array();
        if($this->_store_in == 'database')
        {
            if(isset($user_id)) $where['no_kp'] = $user_id;
            if(isset($date))
            {
                $where['masa >='] = $date.' 00:00:00';
                $where['masa <='] = $date.' 23:59:59';
            }
            if($this->CI->mapplogger->delete_messages($where))
            {
                return TRUE;
            }
        }
        else
        {
            $user_id = (isset($user_id)) ? $user_id : '*';
            $date = (isset($date)) ? $date : '*';
            $files = $this->_store_in.'/log-' . $user_id . '-' . $date . '.php';
            $deleted = 0;
            foreach (glob($files) as $filename)
            {
                if(unlink($filename))
                {
                    $deleted++;
                }
            }
            return $deleted;
        }
    }

    private function _set_directory()
    {
        if($this->_store_in!=='database')
        {
            $this->_store_in = (strlen($this->_store_in) == 0) ? APPPATH . 'logs' : APPPATH.trim($this->_store_in,'/\\');
        }
    }

    private function _verify_settings()
    {
        if($this->_store_in == 'database')
        {
            $this->CI->load->model('core/mapplogger');
        }
        else
        {
            if(!is_really_writable($this->_store_in))
            {
                show_error('Applogger: The directory '.$this->_store_in.' is not writable.');
            }
        }
    }
}
