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

  <div class="">
    <h4 style="display: none;" id="error">Database error. Could not register</h4>
    <h4>Please Register before accessing this page. If already registered, click 'Continue'.</h4>
    <form id="regform" style="display: flex; flex-flow: column; width: 300px">
      <label>
        Username:
        <input type="text" name="username" required>
      </label>
      <label>
        Password:
        <input type="password" minlength="8" maxlength="50" name="pass" required>
      </label>
      <label>
        Email:
        <input type="email" maxlength="100" name="email">
      </label>
      <input type="submit" value="Register">
    </form>
    <br>
    <button id="continue">Continue</button>
  </div>
</body>
</html>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>
  $('#regform').submit(event => {
    event.preventDefault();
    let formData = new FormData(event.target);

    fetch('createuser.php',
          {
           method: 'post',
           body: formData
          }
        ).then(response => response.text())
        .then(result =>
        {
            if(result === "success"){
              window.location = "index.php";
            }
            else{
              $("#error").show();
            }
        });
  });

  $("#continue").click(() => window.location = 'index.php');

</script>
