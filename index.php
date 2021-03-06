<?php
  error_reporting(E_ALL);
  require_once('check_pass.php');
  require_once('auth.php');

  auth();

  ini_set('session.gc_maxlifetime', 60 * 60 * 24);

  session_start();

  if(!isset($_SESSION['start_time'])){
    $_SESSION['start_time'] = time();
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
  }

  if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']){
    die("Access data does not match session data. Page load cancelled");
    session_destroy();
  }

  $cur_mins = round((time() - $_SESSION['start_time']) / 60);
  $cur_hours = floor($cur_mins / 60);
  $cur_mins = $cur_mins % 60;

  require_once('dbConn.class.php');
  require_once('facade/ordersFacade.class.php');
  require_once('tableBuilder.php');

  //set user cookie...domain set false for localhostx
  if(!isset($_COOKIE['userInfo'])){
    setcookie('userInfo', json_encode(['userID' => 'demo', 'lastLogin' => date("m-d-Y H:i:s"), 'visits' => 1]), time() + 60 * 60 * 24 * 365, '/', false);
  }

  $conn = dbConn::getInstance();
  $orders_facade = new ordersFacade($conn);

  $test_conn = $conn->testConnection();

  $customers = $conn->query('SELECT * FROM customers');
  if(!$customers){
    echo "Error: no customers";
  }

  $cust_head = array_keys($customers->fetch_assoc());
  $cust_rows = $customers->fetch_all();

  $orders = $conn->query('SELECT * FROM orders');
  if(!$orders){
    echo "Error: no orders";
  }

  $orders_head = array_keys($orders->fetch_assoc());
  $orders_rows = $orders->fetch_all();

  function sanitizeString($string){
    $string = stripslashes($string);  //removes slashes
    $string = htmlentities($string);  //replaces html tags with proper &lt;, etc.
    $string = strip_tags($string);   //removes angle brackets fully

    return $string;
  }

  function sanitizeSQL($string, $conn){
    $string = $conn->real_escape_string($string);   //escapes special chars

    return sanitizeString($string);
  }
?>

<?php if(isset($_COOKIE['userInfo'])){
    $user_cookie = json_decode($_COOKIE['userInfo'], true);
    $user_cookie['visits']++;
    setcookie('userInfo', json_encode($user_cookie), time() + 60 * 60 * 24 * 365, '/', false);
}
?>

<!DOCTYPE html>
<html lang="en-US" class="index">
<head>
    <meta charset="utf-8">
    <meta name="author" content="KMiskell">
    <meta name="description" content="MYSQLi Practice">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css'>
    <link rel="stylesheet" href="index.css">
    <title>Learn Some MySQLi</title>
</head>
<body>

  <?php if(isset($_COOKIE['userInfo'])){ ?>
    <section>
      <h2>Hello IP <?= $_SERVER['REMOTE_ADDR'] ?>, user <?= $_SERVER['PHP_AUTH_USER']?>. Your first login was <?= $user_cookie['lastLogin']?>. You have visited <?= $user_cookie['visits'] ?> times.</h2>
      <h2>Your current session has lasted for <?= $cur_hours ?> hours, <?= $cur_mins ?> mins.</h2>
      <h2>User Agent info allows webservers to see more data on you. Here is some data on your OS and browser:
        <br><br> <?= $_SESSION['user_agent'] ?><br><br> To prevent this, use a user agent switcher extension.</h2>
      <br><br>
    </section>
<?php } ?>

  <section>
    <h2>DB connection status: <?= $test_conn ?>.</h2>
    <div class="table-container">
      <h4>Customers</h4>
      <table class="practice_table" id="practice_tableA" class="display" style="width: 100%">
          <thead style="font-weight: bold;">
              <?php add_head_row($cust_head); ?>
          </thead>
          <tbody>
              <?php get_row_group($cust_rows); ?>
          </tbody>
      </table>
    </div>

    <br><br><br>

    <div class="table-container">
      <h4>Orders</h4>
      <table class="practice_table" id="practice_tableB" class="display" style="width: 100%">
          <thead style="font-weight: bold;">
              <?php add_head_row($orders_head); ?>
          </thead>
          <tbody>
              <?php get_row_group($orders_rows); ?>
          </tbody>
      </table>
    </div>
  </section>

  <section>
    <form id="cust_pop" action="index.php" method="post">
      <h4>Enter customer ID to populate Update Form</h4>
      <input id='sendID' type='text' name='custID'>
      <input type='submit' value='Populate Customer Edit'>
    </form>
  </section>

  <section>
    <h4>Update Customer Info</h4>
    <form class='flex-form' action="update" method="post">
      <label>
        Title:
        <input type='text' name='title'>
      </label>
      <label>
        Last Name:
        <input type='text' name='last'>
      </label>
      <label>
        First Name:
        <input type='text' name='first'>
      </label>
      <label>
        Phone:
        <input type='text' name='phone'>
      </label>
      <label>
        Unit:
        <input type='text' name='unit'>
      </label>
      <label>
        City:
        <input type='text' name='city'>
      </label>
      <label>
        State:
        <input type='text' name='state'>
      </label>
      <label>
        Zip:
        <input type='text' name='zip'>
      </label>
      <label>
        Country:
        <input type='text' name='country'>
      </label>
      <label>
        Employee Num:
        <input type='text' name='employee num'>
      </label>
      <label>
        Credit Limit:
        <input type='text' name='credit limit'>
      </label>
      <br>
      <input class='submit' type="submit" value="Update Customer">
    </form>
  </section>

  <section>
    <h2 style='color: pink'>Single Responsibility Principle</h2>
    <div style='font-size: 1.5rem; background: green'>
      <p>Problem: Owner wants to calculate number of all orders shipped &#40;actor 1&#41;. Shipping wants to have an alert message if greater than 30% orders not shipped &#40;actor 2&#41;. Order support wants list of all orders from current day &#40;actor 3&#41;.</p>
      <p>Bad design: Put all responsibilities in same <i>orderDetails</i> class.</p>
      <p>Better Design: break each actor into a seperate module and create an single point off access through a single interface class &#40;facade pattern&#41;.</p>
    </div>
    <p>Output:<br><br><?php echo $orders_facade->getNumShipped(); ?></p>
    <p><?php echo $orders_facade->checkUnshippedWarning();?></p>
    <p><?php echo $orders_facade->getShippedToday();?></p>
  </section>

  <section>
    <h2 style='color: pink'>String Sanitizing</h2>
    <pre><code class='php' style='font-size: 1.5rem; background: green'>
      //Use proper semantic hmtl to limit and sanitize as much as possible &#40;ex. &lt;input type='number'&gt; instead of &lt;input type='text'&gt;
      //Still need to do proper sanitizing via PHP before submitting, through. Sanitzation

      function sanitizeString($string){
        $string = stripslashes($string);  //removes slashes
        $string = htmlentities($string);  //replaces html tags with proper &lt;, etc.
        $string = strip_tags($string);   //removes angle brackets fully

        return $string;
      }

      function sanitizeSQL($string, $conn){
        $string = $conn->real_escape_string($string);   //escapes special chars

        return sanitizeString($string);
      }
      </code>
    </pre>
</body>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready( function () {
  $('#practice_tableA').DataTable({
      'autowidth': false
  });
  $('#practice_tableB').DataTable({
      'autowidth': false
  });
});

$('#cust_pop').submit(function(event){
  event.preventDefault();

  $.ajax({
    url: 'get_customer.php',
    type: 'GET',
    data: $('#cust_pop').serialize(),
    dataType: 'json',
    success: function(response){
      for(key in response){
        $(`input[name="${key}"]`).val(response[key]);
      }
    },
    error: function(response){
      alert('Cannot find customer info');
    }
  });

});
</script>

</html>

<?php
  $conn->close();
  $customers->close();
?>
