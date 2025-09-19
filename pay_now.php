<?php 

  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');

  require('inc/paytm/config_paytm.php');
  require('inc/paytm/encdec_paytm.php');
  require('inc/Payhere.php'); 

  date_default_timezone_set("Asia/Colombo");

  session_start();

  if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
  }

  if(isset($_POST['pay_now']))
  {
    header("Pragma: no-cache");
    header("Cache-Control: no-cache");
    header("Expires: 0");

    

    $ORDER_ID = 'ORD_'.$_SESSION['uId'].random_int(11111,9999999);    
    $CUST_ID = $_SESSION['uId'];
    $TXN_AMOUNT = $_SESSION['room']['payment'];

    
    $frm_data = filteration($_POST);

    $queryMealPlans = "SELECT * FROM room_meal_plans WHERE id  = ?";
    $meal_option = select($queryMealPlans,[$frm_data['meal_option']],'i');
    $meal_option = mysqli_fetch_assoc($meal_option);
    $meal_option_id = $meal_option['id'];

    // calculate night count
    $checkin = new DateTime($frm_data['checkin']);
    $checkout = new DateTime($frm_data['checkout']);
    $interval = $checkin->diff($checkout);
    $nightCount = $interval->days;
    $meal_price = (float) $meal_option['price_modifier'] * $nightCount ;

    $payement_type = $frm_data['ptype'];
    $booking_status = 'booked';

    $TXN_AMOUNT = $TXN_AMOUNT + $meal_price;
    $payble = $TXN_AMOUNT;
    if($payement_type == 'half'){
        $TXN_AMOUNT = ($TXN_AMOUNT) / 2;
        $_SESSION['room']['payment'] = $TXN_AMOUNT;
    }else if ($payement_type == 'skip') {
        $TXN_AMOUNT = 0;
        $_SESSION['room']['payment'] = $TXN_AMOUNT;
        $booking_status = 'pending';
    }

    $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`,`booking_status`,`order_id`,`trans_id`,`trans_amt`,`trans_status`,`trans_resp_msg`) VALUES (?,?,?,?,?,?,?,?,?,?)";

    insert($query1,[$CUST_ID,$_SESSION['room']['id'],$frm_data['checkin'],
      $frm_data['checkout'],$booking_status,$ORDER_ID, $ORDER_ID ,$_SESSION['room']['payment'],'TXN_SUCCESS','Txn Success' ],'isssssssss');
    
    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`,
      `user_name`, `phonenum`, `address`, `meal_plan_id`, `meal_plan_price`) VALUES (?,?,?,?,?,?,?,?,?)";

    insert($query2,[$booking_id,$_SESSION['room']['name'],$payble,
      $TXN_AMOUNT,$frm_data['name'],$frm_data['phonenum'],$frm_data['address'],$meal_option_id,$meal_price],'issssssss');

    $order_id = $ORDER_ID; 
    $room_name = $_SESSION['room']['name'];
    $amount = $TXN_AMOUNT;

    $payhere = new Payhere();
    $merchant_id = $payhere->getMerchantId();
    $currency = $payhere->getCurrancy();
    $hash = $payhere->generateHash($order_id, $amount);
    $email = 'abc@gmail.com';
  }

?>

<html>
<head>
    <title>Processing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(140deg, #1a1a2e 0%, #16213e 35%, #0f3460 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .payment-container {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            align-items: start;
        }

        .main-form {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .payment-sidebar {
            background: linear-gradient(145deg, #2d3748, #4a5568);
            border-radius: 24px;
            padding: 30px;
            color: white;
            position: sticky;
            top: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .brand-header {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .brand-logo i {
            font-size: 24px;
            color: white;
        }

        .brand-text h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 5px;
        }

        .brand-text p {
            color: #718096;
            font-size: 14px;
        }

        .alert-box {
            background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
            border: none;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            box-shadow: 0 8px 25px rgba(253, 203, 110, 0.3);
        }

        .alert-box i {
            font-size: 24px;
            color: #d63031;
            margin-right: 15px;
        }

        .alert-text {
            color: #2d3436;
            font-weight: 600;
            font-size: 16px;
        }

        .form-section {
            margin-bottom: 35px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f7fafc;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .section-icon i {
            color: white;
            font-size: 18px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .input-group {
            position: relative;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .input-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .input-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .order-summary {
            margin-bottom: 30px;
        }

        .summary-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .summary-header i {
            font-size: 24px;
            margin-right: 10px;
            color: #f6ad55;
        }

        .summary-title {
            font-size: 20px;
            font-weight: 700;
        }

        .amount-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            margin: 20px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .amount-label {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .amount-value {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .room-details {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .room-details h4 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #f6ad55;
        }

        .room-details p {
            opacity: 0.9;
            font-size: 18px;
            font-weight: 600;
        }

        .security-badge {
            display: flex;
            align-items: center;
            background: rgba(72, 187, 120, 0.2);
            border: 1px solid rgba(72, 187, 120, 0.3);
            border-radius: 12px;
            padding: 15px;
            margin: 25px 0;
        }

        .security-badge i {
            color: #48bb78;
            font-size: 20px;
            margin-right: 12px;
        }

        .security-text {
            color: #2f855a;
            font-size: 14px;
            font-weight: 600;
        }

        .submit-button {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .submit-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.5);
        }

        .submit-button:active {
            transform: translateY(-1px);
        }

        .payhere-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .payhere-footer p {
            font-size: 12px;
            opacity: 0.7;
            margin-bottom: 8px;
        }

        .payhere-logo {
            font-size: 16px;
            font-weight: 700;
            color: #f6ad55;
        }

        @media (max-width: 1024px) {
            .payment-container {
                grid-template-columns: 1fr;
                max-width: 600px;
            }
            
            .payment-sidebar {
                position: static;
                order: -1;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .main-form {
                padding: 25px;
            }
            
            .payment-sidebar {
                padding: 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .brand-text h1 {
                font-size: 24px;
            }
            
            .amount-value {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <!-- Main Form Section -->
        <div class="main-form">
            <div class="brand-header">
                <div class="brand-logo">
                    <i class="fas fa-hotel"></i>
                </div>
                <div class="brand-text">
                    <h1>Secure Payment</h1>
                    <p>Complete your hotel booking</p>
                </div>
            </div>

            <div class="alert-box">
                <i class="fas fa-info-circle"></i>
                <div class="alert-text">Please do not refresh this page during payment processing</div>
            </div>

            <form method="post" action="https://sandbox.payhere.lk/pay/checkout">   
                <input type="hidden" name="merchant_id" value="<?php echo $merchant_id ?>">
                <input type="hidden" name="return_url" value="http://localhost/hms/order-success.php">
                <input type="hidden" name="cancel_url" value="http://localhost/hms/order-success.php">
                <input type="hidden" name="notify_url" value="http://localhost/hms/order-success.php">
                <input type="hidden" name="hash" value="<?php echo $hash; ?>">

                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="section-title">Booking Details</div>
                    </div>
                    <div class="form-grid">
                        <div class="input-group">
                            <label>Order ID</label>
                            <input type="text" name="order_id" value="<?php echo $order_id; ?>" readonly>
                        </div>
                        <div class="input-group">
                            <label>Room Type</label>
                            <input type="text" name="items" value="<?php echo $room_name; ?>" readonly>
                        </div>
                        <div class="input-group">
                            <label>Currency</label>
                            <input type="text" name="currency" value="<?php echo $currency; ?>" readonly>
                        </div>
                        <div class="input-group">
                            <label>Amount</label>
                            <input type="text" name="amount" value="<?php echo number_format($amount, 2, '.', ''); ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="section-title">Guest Information</div>
                    </div>
                    <div class="form-grid">
                        <div class="input-group">
                            <label>Full Name</label>
                            <input type="text" name="first_name" value="<?php echo $frm_data['name']; ?>" readonly>
                        </div>
                        <div class="input-group" style="display:none">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="<?php echo $frm_data['name']; ?>" readonly>
                        </div>
                        <div class="input-group" style="display:none">
                            <label>Email Address</label>
                            <input type="text" name="email" value="<?php echo $email; ?>" readonly>
                        </div>

                        <div class="input-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="<?php echo $frm_data['phonenum']; ?>" readonly>
                        </div>
                    </div>

                    <div class="input-group" style="margin-top: 20px;">
                        <label>Address</label>
                        <input type="text" name="address" value="<?php echo $frm_data['address']; ?>" readonly>
                    </div>
                    <div class="form-grid " style="display:none">
                        <div class="input-group">
                            <label>City</label>
                            <input type="text" name="city" value="<?php echo $frm_data['name']; ?>" readonly>
                        </div>
                        <input type="hidden" name="country" value="<?php echo $frm_data['name']; ?>" readonly>
                    </div>
                </div>

                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    <div class="security-text">Your payment is secured with 256-bit SSL encryption</div>
                </div>

                <?php if($payement_type != 'skip'){ ?>
                    <input type="submit" value="Complete with Payment " class="submit-button" style="margin-bottom:40px">
                <?php }else {?>
                    <a href="order-success.php" class="submit-button" style="text-decoration:none;text-align:center">Skip Payment</a>
                <?php } ?>
            </form>
        </div>

        <!-- Payment Sidebar -->
        <div class="payment-sidebar">
            <div class="order-summary">
                <div class="summary-header">
                    <i class="fas fa-calculator"></i>
                    <div class="summary-title">Order Summary</div>
                </div>
                
                <div class="amount-card">
                    <div class="amount-label">Total Amount</div>
                    <div class="amount-value"><?php echo $currency; ?> <?php echo number_format($amount, 2, '.', ''); ?></div>
                </div>

                <div class="room-details">
                    <h4>Room Details</h4>
                    <p><?php echo $room_name; ?></p>
                </div>

                <div class="room-details">
                    <h4>Order ID</h4>
                    <p><?php echo $order_id; ?></p>
                </div>
            </div>

            <div class="payhere-footer">
                <p>Powered by</p>
                <div class="payhere-logo">PayHere Payment Gateway</div>
            </div>
        </div>
    </div>
</body>
</html>