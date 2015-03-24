<html>
<head>
<?php require_once(__DIR__ . "/base.php"); ?>
<?php require_once(__DIR__ . "/../bootstrap.php"); ?> 
<title><?php echo $application->applicationTitle; ?></title>

<!-- 
  Catapult Bandwidth SIP Domains 009 interface
-->

<body>
  <?php generateMenu(); ?>
  <div class="app-content">

       <h3>Creating SIP Domains</h3>
      <p>
      Below you can either create domains or endpoints, remember you will need atleast
      one domain in order to create an endpoint
      </p>
      <a href="./">
        <button>
         Go Back
        </button>
      </a>
        <br />
        <br />
      <div class="box">
       <div class="status <?php echo $status; ?>"></div><small><b>Status: </b><?php echo $message; ?></small>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
     <ul id="sip-domains"> 
       <h5><b>Create A Domain</b></h5>
        <hr />
      <?php if (isset($domainsResult)): ?>
        <?php if (isset($domainsSuccess) && $domainsSuccess): ?>
          <h2 class='success'><?php echo $domainsResult; ?></h2>
        <? else: ?> 
          <h2 class="error"><?php echo $domainsResult; ?></h2>
        <?php endif ; ?>
      <?php endif; ?>

      <?php echo generateForm($form['domains']);?>

     </ul> 

     <ul id="sip-domains">
      <h5><b>Create An Endpoint</b></h5>
        <hr />
       <?php if (isset($endpointsResult)): ?>
        <?php if($endpointsSuccess): ?>  
          <h2 class="success"><?php echo $endpointsResult; ?></h2>
        <? else: ?>
          <h2 class="error"><?php echo $endpointsResult; ?></h2>
        <? endif; ?>
      <?php endif; ?>
       <?php echo generateForm($form['endpoints']); ?>
     </ul>

  <script>
    // minimal 
    // jQuery to 
    // allow validation 
    var inputs = $('inputs').get().concat($('select').get());
    var alertColor = '#ffe161';
    var initialColor = '#fff';
     
  
    var retainColor = function(el) {
      el.style.backgroundColor = initialColor;
      remPlaceholderMessage(el); 
    }; 
    var remPlaceholderMessage = function(el) {
      el.setAttribute("placeholder","");
    };
    var setPlaceholderMessage = function(el) {
      el.setAttribute("placeholder", "This needs to be filled");
    };
    var disposeColor = function(el) {
      el.style.backgroundColor = alertColor;
      setPlaceholderMessage(el);
    };
    $('input[type=submit]').click(function(e, el) {
        e.preventDefault();
        var needs = 0;
        var frmInputs = e.target.parentNode.childNodes; 

        for (var i in frmInputs) {
          if (frmInputs[i].value==='') {
            disposeColor(frmInputs[i]);
            frmInputs[i].onfocus = function() {
              return retainColor(this);
            };
            needs++;
          }
        }
        if (needs > 0) {
          return;
        }

        e.target.parentNode.submit();
    }); 

     $('#domainSelect').change(function(e) {
        var that = e.target;
        var dId = that.value; 

        $(".endpoints").hide();
        $("#" + dId).show();
     });
  </script>

</body>
