<?php
namespace App\Http;
//use App\Dimention as Dimention;
use Mail;
use Illuminate\Support\Facades\DB AS DB;
class Helpers
{
	private $locales = array();
	private $settings = array(
		'user_online_time_flag' => (2 * 60), // 2 min
		'post_comments_per_page' => 5,
	);
	
	
	public static function generateOTP($digits = 5){
		$i = 1; //counter
		$pin = ""; //our default pin is blank.
		while($i < $digits){
			//generate a random number between 1 and 9.
			$pin .= mt_rand(1, 9);
			$i++;
		}
		return $pin;
	}
	
	public static function asset_url($file) {
		return url('/public/front/' . $file);
	}
	public static function get_settings() {
		$Helpers = new Helpers;
		$settings = $Helpers->settings;
		return $settings;
	}
	public static function getCurrentURL()
	{
	    $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
	    $currentURL .= $_SERVER["SERVER_NAME"];
	    if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
	    {
	        $currentURL .= ":".$_SERVER["SERVER_PORT"];
	    }
	    $currentURL .= $_SERVER["REQUEST_URI"];
	    return $currentURL;
	}
	public static function paginate($item_per_page, $current_page, $total_records, $page_url, $additional_params="", $additional_class="")
	{
		$v = ($total_records/$item_per_page);
		if($v > (int)$v)
			$total_pages = (int)$v + 1;
		else
			$total_pages = (int)$v;
	    $pagination = '';
	    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
	        $pagination .= '<ul class="pagination '.$additional_class.'">';
	        $right_links    = $current_page + 3;
	        $previous       = $current_page - 3; //previous link
	        $next           = $current_page + 1; //next link
	        $first_link     = true; //boolean var to decide our first link
	        if($current_page > 1){
	            $previous_link = ($previous<=0)?1:$previous;
	            $pagination .= '<li class="first"><a href="'.$page_url.'?pg=1'.$additional_params.'" title="First">&laquo;</a></li>'; //first link
	            $pagination .= '<li><a href="'.$page_url.'?pg='.$previous_link.''.$additional_params.'" title="Previous">&lt;</a></li>'; //previous link
	                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
	                    if($i > 0){
	                        $pagination .= '<li><a href="'.$page_url.'?pg='.$i.''.$additional_params.'">'.$i.'</a></li>';
	                    }
	                }
	            $first_link = false; //set first link to false
	        }
	        if($first_link){ //if current active page is first link
	            $pagination .= '<li class="first active"><a href="'.$page_url.'?pg='.$current_page.''.$additional_params.'">'.$current_page.'</a></li>';
	        }elseif($current_page == $total_pages){ //if it's the last active link
	            $pagination .= '<a href="'.$page_url.'?pg='.$current_page.''.$additional_params.'"><li class="last active">'.$current_page.'</a></li>';
	        }else{ //regular current link
	            $pagination .= '<li class="active"><a href="'.$page_url.'?pg='.$current_page.''.$additional_params.'">'.$current_page.'</a></li>';
	        }
	        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
	            if($i<=$total_pages){
	                $pagination .= '<li><a href="'.$page_url.'?pg='.$i.''.$additional_params.'">'.$i.'</a></li>';
	            }
	        }
	        if($current_page < $total_pages){
	                $next_link = ($i > $total_pages)? $total_pages : $i;
	                $pagination .= '<li><a href="'.$page_url.'?pg='.$next_link.''.$additional_params.'" >&gt;</a></li>'; //next link
	                $pagination .= '<li class="last"><a href="'.$page_url.'?pg='.$total_pages.''.$additional_params.'" title="Last">&raquo;</a></li>'; //last link
	        }
	        $pagination .= '</ul>';
	    }
	    return $pagination; //return pagination links
	}
	public static function pmail($data) {
		if(env('FORCE_STOP_MAIL') == '1') {
			$return = [];
			$return['success'] = 1;
			$return['message'] = "Message has been sent successfully";
			return $return;
		}
		require_once(base_path() . '/phpmailer/PHPMailer-master/src/PHPMailer.php');
		require_once(base_path() . '/phpmailer/PHPMailer-master/src/SMTP.php');
		require_once(base_path() . '/phpmailer/PHPMailer-master/src/OAuth.php');
		require_once(base_path() . '/phpmailer/PHPMailer-master/src/Exception.php');
		$return = array('success' => '', 'message' => '');
		/*$Host = $data['host'] ?? env('MAIL_HOST');
		$Username = $data['username'] ?? env('MAIL_USERNAME');
		$Password = $data['password'] ?? env('MAIL_PASSWORD');
		$SMTPSecure = $data['smtp_secure'] ?? env('MAIL_ENCRYPTION');
		$Port = $data['port'] ?? env('MAIL_PORT');
		$FromName = $data['from_name'] ?? 'Support';
		if(env('TEST_MAIL_MODE') == 1) {
			$Host = env('MAIL_HOST');
			$Username = env('MAIL_USERNAME');
			$Password = env('MAIL_PASSWORD');
			$SMTPSecure = env('MAIL_ENCRYPTION');
			$Port = env('MAIL_PORT');
		}*/
		$Host = env('MAIL_HOST');
		$Username = env('MAIL_USERNAME');
		$Password = env('MAIL_PASSWORD');
		$SMTPSecure = env('MAIL_ENCRYPTION');
		$Port = env('MAIL_PORT');
		$FromName = $data['from_name'] ?? '';
        // dd($Host,$Username,$Password);
		if($FromName == '') $FromName = env('MAIL_FROMNAME');
		//echo '<pre>'; print_r($data); echo '<br>' . $Host . '<br>' . $Username . '<br>' . $Password . '<br>' . $SMTPSecure . '<br>' . $Port;  die;
		$mail = new \PHPMailer\PHPMailer\PHPMailer();
		$mail->SMTPDebug = 0;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->Host = $Host;
		$mail->SMTPAuth = true;
		$mail->Username = $Username;
		$mail->Password = $Password;
		$mail->SMTPSecure = $SMTPSecure;
		$mail->Port = $Port;
		$mail->From = $Username;
		$mail->FromName = $FromName;
		if(env('TEST_MAIL_ADDRESS') == '') {
			foreach ($data['to'] as $value) {
				$mail->addAddress($value[0], $value[1]);
			}
			if(isset($data['cc']) && is_array($data['cc']) && count($data['cc']) > 0) {
				foreach ($data['cc'] as $value) {
					$mail->addCC($value[0], $value[1]);
				}
			}
		} else {
			$mail->addAddress(env('TEST_MAIL_ADDRESS'), '');
		}
		$mail->isHTML(true);
		$mail->Subject = $data['subject'];
		$mail->Body = $data['body'];
		if(isset($data['reply_to']) && count($data['reply_to']) > 0) {
			foreach ($data['reply_to'] as $value) {
				$mail->AddReplyTo($value[0], $value[1]);
			}
		}
		if(isset($data['attachment']) && count($data['attachment']) > 0) {
			foreach ($data['attachment'] as $value) {
				$mail->AddAttachment($value);
			}
		}
		if(!$mail->send())
		{
		    $return['success'] = 0;
		    $return['message'] = $mail->ErrorInfo;
		}
		else
		{
		    $return['success'] = 1;
		    $return['message'] = "Message has been sent successfully";
		}
		return $return;
	}
	public static function pmailnoSMTP($data) {
		require_once(base_path() . '/phpmailer/PHPMailer-master/src/PHPMailer.php');
		require_once(base_path() . '/phpmailer/PHPMailer-master/src/SMTP.php');
		require_once(base_path() . '/phpmailer/PHPMailer-master/src/OAuth.php');
		require_once(base_path() . '/phpmailer/PHPMailer-master/src/Exception.php');
		$return = array('success' => '', 'message' => '');
		$mail = new \PHPMailer\PHPMailer\PHPMailer();
		$mail->From = $data['from'];
		$mail->FromName = $data['from_name'];
		foreach ($data['to'] as $value) {
			$mail->addAddress($value[0], $value[1]);
		}
		$mail->isHTML(true);
		$mail->Subject = $data['subject'];
		$mail->Body = $data['body'];
		if(isset($data['reply_to']) && count($data['reply_to']) > 0) {
			foreach ($data['reply_to'] as $value) {
				$mail->AddReplyTo($value[0], $value[1]);
			}
		}
		if(isset($data['attachment']) && count($data['attachment']) > 0) {
			foreach ($data['attachment'] as $value) {
				$mail->AddAttachment($value);
			}
		}
		if(!$mail->send())
		{
		    $return['success'] = 0;
		    $return['message'] = $mail->ErrorInfo;
		}
		else
		{
		    $return['success'] = 1;
		    $return['message'] = "Message has been sent successfully";
		}
		return $return;
	}
	public static function resize_image($tmp_name, $mime_type, $maxDim, $target_filename) {
        $fn = $tmp_name;
        $size = getimagesize( $fn );
        $ratio = $size[0]/$size[1]; // width/height
        if( $ratio > 1) {
            $width = $maxDim;
            $height = $maxDim/$ratio;
        } else {
            $width = $maxDim*$ratio;
            $height = $maxDim;
        }
        $src = imagecreatefromstring( file_get_contents( $fn ) );
        $dst = imagecreatetruecolor( $width, $height );
        if($mime_type == 'image/png') {
			imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
			imagealphablending($dst, false);
			imagesavealpha($dst, true);
		}
        imagecopyresampled( $dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1] );
        imagedestroy( $src );
        if($mime_type == 'image/jpeg')
        	imagejpeg( $dst, $target_filename ); // adjust format as needed
        if($mime_type == 'image/png')
        	imagepng( $dst, $target_filename );
        imagedestroy( $dst );
	}
	public static function resize_crop_image($tmp_name, $mime_type, $maxW, $maxH, $target_filename, $quality = 9) {
        $fn = $tmp_name;
        $size = getimagesize( $fn );
        $ratio = $size[0]/$size[1]; // width/height
        $width = $size[0];
        $height = $size[1];
        /*if( $ratio > 1) {
            $width = $maxW;
            $height = $maxW/$ratio;
        } else {
            $width = $maxW*$ratio;
            $height = $maxW;
        }*/
        $width_new = $size[1] * $maxW / $maxH;
    	$height_new = $size[0] * $maxH / $maxW;
        $src = imagecreatefromstring( file_get_contents( $fn ) );
        $dst = imagecreatetruecolor( $maxW, $maxH );
        if($mime_type == 'image/png') {
			imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
			imagealphablending($dst, false);
			imagesavealpha($dst, true);
		}
        if($width_new > $width){
	        $h_point = (($height - $height_new) / 2);
	        imagecopyresampled($dst, $src, 0, 0, 0, $h_point, $maxW, $maxH, $width, $height_new);
	    }else{
	        $w_point = (($width - $width_new) / 2);
	        imagecopyresampled($dst, $src, 0, 0, $w_point, 0, $maxW, $maxH, $width_new, $height);
	    }
        //imagecopyresampled( $dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1] );
        imagedestroy( $src );
        if($mime_type == 'image/jpeg')
        	imagejpeg( $dst, $target_filename, $quality ); // adjust format as needed
        if($mime_type == 'image/png')
        	imagepng( $dst, $target_filename, $quality );
        imagedestroy( $dst );
	}
	public static function isMobileDevice() {
	    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	public static function stripe_payment($params = []) {
	    $card_number = $params['card_number'] ?? '';
	    $exp_month = $params['exp_month'] ?? '';
	    $exp_year = $params['exp_year'] ?? '';
	    $card_cvv = $params['card_cvv'] ?? '';
	    $order_total = $params['order_total'] ?? '';
	    $currency = $params['currency'] ?? 'eur';
	    $description = $params['description'] ?? '';
	    $customer_data = $params['customer_data'] ?? [];
	    $currency = strtolower($currency);
	    //$secret_key = $_ENV['STRIPE_API_KEY'];
	    $return = ['success' => 0, 'message' => '', 'txn_id' => '', 'payment_data' => []];
	    require_once (base_path() . '/stripe-php-4.4.2/init.php');
	    //\Stripe\Stripe::setApiKey('sk_live_pNFn0VRVqeBYldZ5dWUOvvhd'); // client secrete key
	    \Stripe\Stripe::setApiKey('sk_test_HW4cwR4Ak6xVaSOKHOUPRise'); // sudipta test secrete key
	    //\Stripe\Stripe::setApiKey($secret_key);
	    try {
	        $card_token=\Stripe\Token::create(array(
	          "card" => array(
	            "number" => $card_number,
	            "exp_month" => $exp_month,
	            "exp_year" => $exp_year,
	            "cvc" => $card_cvv
	          )
	        ));
	        try {
	            $customer = \Stripe\Customer::create(array_merge(['source' => $card_token->id], $customer_data));
	            try {
	              $charge=\Stripe\Charge::create(array(
	                  'customer' => $customer->id,
	                  "amount" => ($order_total * 100),
	                  "currency" => $currency,
	                  //"source" => $card_token->id,
	                  "description" => $description
	                ));
	                $stripeobject = new \Stripe\StripeObject($charge);
					// dd($stripeobject);
	                $stripearray = $stripeobject->__toArray(true);
	                //print_r($stripearray)."<br><br>";
	                $charge_id = $stripearray['id']['id'];
	                $txn_id = $stripearray['id']['balance_transaction'];
	                $customer = $stripearray['id']['customer'];
	                $return['success'] = 1;
	                $return['txn_id'] = $txn_id;
	                $return['charge_id'] = $charge_id;
	                $return['customer'] = $customer;
	                $return['payment_data'] = $stripearray;
	              } catch(\Stripe\Error\Card $e) {
	                  $return['message'] = $e->getMessage();
	              }
	            } catch(\Stripe\Error\InvalidRequest $e) {
	                $return['message'] = $e->getMessage();
	            }
	    } catch(\Stripe\Error\Authentication $e) {
	        $return['message'] = $e->getMessage();
	    } catch(\Stripe\Error\Card $e) {
	        $return['message'] = $e->getMessage();
	    } catch(\Stripe\Error\InvalidRequest $e) {
	        $return['message'] = $e->getMessage();
	    }
	    return $return;
	}
	public static function stripe_subscription_payment($params = []) {
	    $card_number = $params['card_number'] ?? '';
	    $exp_month = $params['exp_month'] ?? '';
	    $exp_year = $params['exp_year'] ?? '';
	    $card_cvv = $params['card_cvv'] ?? '';
	    $order_total = $params['order_total'] ?? '';
	    $currency = $params['currency'] ?? 'eur';
	    $description = $params['description'] ?? '';
	    $customer_data = $params['customer_data'] ?? [];
	    $plan_data = $params['plan_data'] ?? [];
	    $currency = strtolower($currency);
	    //$secret_key = $_ENV['STRIPE_API_KEY'];
	    $return = ['success' => 0, 'message' => '', 'txn_id' => '', 'payment_data' => []];
	    require_once (base_path() . '/stripe-php-4.4.2/init.php');
	    //\Stripe\Stripe::setApiKey('sk_live_pNFn0VRVqeBYldZ5dWUOvvhd'); // client secrete key
	    \Stripe\Stripe::setApiKey('sk_test_51LhTXQG3x7CP9tWN8rZ3r9kUSTW0mIo5N7Lgrckj1pUpf0YQwxrXdTObXDiO2sPhTVuErIUegUjmOMWN1THJX8Xk00DPEmpRuY'); // sudipta test secrete key
	    //\Stripe\Stripe::setApiKey($secret_key);
	    try {
	        $card_token=\Stripe\Token::create(array(
	          "card" => array(
	            "number" => $card_number,
	            "exp_month" => $exp_month,
	            "exp_year" => $exp_year,
	            "cvc" => $card_cvv
	          )
	        ));
	        try {
	            $customer = \Stripe\Customer::create(array_merge(['source' => $card_token->id], $customer_data));
	            try {
	            	$plan = \Stripe\Plan::create(array(
				            "product" => [
				                "name" => $plan_data['name']
				            ],
				            "amount" => ($order_total * 100),
				            "currency" => $currency,
				            "interval" => $plan_data['interval'],
				            "interval_count" => $plan_data['interval_count']
				        ));
	              try {
	              	$subscription = \Stripe\Subscription::create(array(
			                "customer" => $customer->id,
			                "items" => array(
			                    array(
			                        "plan" => $plan->id,
			                    ),
			                ),
			            ));
	              	$stripeobject = new \Stripe\StripeObject($subscription);
                      //dd($stripeobject);
	                $stripearray = $stripeobject->__toArray(true);
                    // $stripearray = $stripeobject->toArray();
	                //print_r($stripearray)."<br><br>"; die;
	                $subscription_id = $stripearray['id']['id'];
	                $customer = $stripearray['id']['customer'];
	                $plan_id = $stripearray['id']['plan']['id'];
	                $return['subscription_id'] = $subscription_id;
	                $return['plan_id'] = $plan_id;
	                $return['success'] = 1;
	                $return['customer'] = $customer;
	                $return['payment_data'] = $stripearray;
	              } catch(\Stripe\Error\Card $e) {
	                  $return['message'] = $e->getMessage();
	              }
	              } catch(\Stripe\Error\Card $e) {
	                  $return['message'] = $e->getMessage();
	              }
	            } catch(\Stripe\Error\InvalidRequest $e) {
	                $return['message'] = $e->getMessage();
	            }
	    } catch(\Stripe\Error\Authentication $e) {
	        $return['message'] = $e->getMessage();
	    } catch(\Stripe\Error\Card $e) {
	        $return['message'] = $e->getMessage();
	    } catch(\Stripe\Error\InvalidRequest $e) {
	        $return['message'] = $e->getMessage();
	    }
	    return $return;
	}
	public static function stripe_subscription_cancel($params = []) {
	    $subscription_id = $params['subscription_id'] ?? '';
	    $return = ['success' => 0, 'message' => '', 'txn_id' => '', 'payment_data' => []];
	    $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/subscriptions/' . $subscription_id);
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
			$authorization = 'Authorization: Bearer sk_test_HW4cwR4Ak6xVaSOKHOUPRise';
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			/*echo 'HTTP Status Code: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . PHP_EOL;
			echo 'Response Body: ' . $response . PHP_EOL;*/
			curl_close($ch);
			$response = json_decode($response, true);
			//echo '<pre>' . print_r($response, true); die;
			$return['response_data'] = $response;
			if(isset($response['status']) && $response['status'] == 'canceled') {
				$return['success'] = 1;
			}
			return $return;
	}
}
?>
