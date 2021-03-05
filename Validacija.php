
<?php
    class Validacija {
        private $original = "";
        private $valid = "";
        private $msg = "";

        public static $isValidForm = true;


//
        public function setOriginal($original){
            $this->original = $original;
        }
        public function setValid($valid){
            $this->valid = $valid;
        }
        public function setMsg($msg){
            $this->msg = $msg;
        }

        public function getOriginal() {
            return $this->original;
        }
        public function getValid() {
            return $this->valid;
        }
        public function getMsg() {
            return $this->msg;
        }
//

        public function validText($data, $minLen, $maxLen, $reg) {
            $msg = "";
            $original = $data;
            $data = $this->test_input($data);
            if($data != $original) {
                $msg .= "<span class='maybe'>Izbaceni su nepozeljni karakteri</span>";
            }
    
            if (strlen($data) < $minLen || strlen($data) > $maxLen) {
                $msg .= "<span class='error'>Polje mora sadrzati $minLen-$maxLen karaktera</span>";
            }
    
            if (!preg_match("/^[$reg]*$/", $data)) {
                $msg .= "<span class='error'>Dozvoljeni karakteri: $reg</span>";
                $izbaci = preg_replace("/[$reg]/", '', $data);
                $msg .= "<span class='maybe'>Nedozvoljeni karakteri: $izbaci</span>";
                $data = preg_replace("/[^$reg]/", '', $data);
            }            
    
            if(empty($msg)) {
                $msg = "<span class='success'>OK</span>";
            } else {
                self::$isValidForm = false;
            }

            $this->setFields($original, $data, $msg);
        }
    
        public function validDate($data, $od, $do) {
            if($data != "") {
                if($data < $od || $data > $do) {
                    self::$isValidForm = false;
                    $msg =  "<span class='error'>Datum mora biti izmedju $od i $do</span>";
                } else {
                    $msg = "<span class='success'>OK</span>";
                }
                $this->setFields($data, $data, $msg);
            } else {
                $msg = "<span class='success'>OK</span>";
                $this->setFields($data, "", $msg);
            }
        }
    
        public function validRadio($data, $in) {
            if (in_array($data, $in)) {
                $msg = "<span class='success'>OK</span>";
            } else {
                self::$isValidForm = false;
                $msg =  "<span class='error'>Izaberite jednu od opcija.</span>";
            }

            $this->setFields($data, $data, $msg);
        }
    
        public function validRetype($rpass, $pass) {
            if($rpass == $pass) {
                $msg = "<span class='success'>OK</span>";
            } else {
                self::$isValidForm = false;
                $msg = "<span class='error'>Retype Password!</span>";
            }

            $this->setFields($rpass, $rpass, $msg);
        }
    
        private function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);

            $data = preg_replace('/\s+/', ' ', $data);
            return $data;
        }

        private function setFields($original, $valid, $msg) {
            $this->original = $original;
            $this->valid = $valid;
            $this->msg = $msg;
        }

    }
?>