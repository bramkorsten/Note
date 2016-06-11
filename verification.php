<div class=" <?php
            if ($error == 'postsubmitted') {
              echo ('valid');
              $validpost = true;
              $message = "Post published!";
            }
            else if ($error == 'postnonexist') {
              echo ('unvalid');
              $validpost = false;
              $message = "This post does not exist!";
            }
            else if ($_GET['ref'] == "postsubmitcancelled") {
              echo ('unvalid');
              $validpost = false;
              $message = "Post cancelled!";
            }
            else if ($error == 'postsubmitfailed') {
              echo ('unvalid');
              $validpost = false;
              $message = "Something went wrong :(";
            }
            unset($_SESSION['error']);
            ?> post-verification pre">
  <div class="checkmark pre">
      <?php
       if ($validpost) {
        echo ('<svg viewBox="0 0 24 24">
        <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
      </svg>');
       }
       else {
        echo ('<svg viewBox="0 0 24 24">
        <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
      </svg>');
       }
      ?>
  </div>
</div>

<div class="message">
  <p>
    <?php echo ($message)?>
  </p>
  <div class="arrow-down"></div>
</div>