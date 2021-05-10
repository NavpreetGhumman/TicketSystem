
<?php

session_start();
$username =$_SESSION['username'];
$loggedUserEmail =  $_SESSION['loggedUserEmail'];
$userIdLogged = $_SESSION['loggedUserId'];
$loggedUserRole = $_SESSION['loggedUserRole'];

if(!isset($_SESSION['username']))
{
    header('Location: login.php');
}

date_default_timezone_set("America/Toronto");

$msgList = "";
$tickets = simplexml_load_file("ticket.xml");
$users = simplexml_load_file("users.xml");

$id = $_GET['id'];

foreach ($tickets->ticket as $p) {
    if ($id == $p->attributes()) {
        $ticketId = $p->attributes();
        $openDate = $p->openDate;
        $category = $p->ticketCategory;
        $userId = $p->clientId;
        $status = $p->ticketStatus;
        $msgList = $p->ticketMessage;
        $subject = $p->ticketSubject;
    }}

if(isset($_POST['addMessage']))
{
    $ticketMessage = $_POST['message'];
    $tId = $_POST['tId'];

    if($tId == $ticketId) {

        $ticketDate = date("Y-m-d");
        $ticketTime = date("h:i:s");
        $ticket= $tickets->xpath("/tickets/ticket[@ticketId=$tId]")[0];

        $message = $ticket->addChild('ticketMessage');
        $description = $message->addChild('description', $ticketMessage);
        $description->addAttribute('userId', $userIdLogged);
        $description->addAttribute('date', $ticketDate);
        $description->addAttribute('time', $ticketTime);

       $tickets->saveXML("ticket.xml");
   }
}
?>

<?php
require_once 'header.php';
?>
<header class="header">
    <div class="page-container">
        <nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <p class="site-logo"><i class="fas fa-laptop"></i><span>Service Now</span></p>
                </a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link-logout" aria-current="page" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<body>

<div class="page-container">
    <div class="container">
        <div class="ticket-heading">Ticket Details</div>
        <a href="staffView.php">back to list</a>
        <div class="jumbotron">
            <dl class="row">
            <dt class="col-sm-3">Ticket ID:</dt>
            <dd class="col-sm-9"><?php print $ticketId ?></dd>

            <dt class="col-sm-3">Issue Date:</dt>
            <dd class="col-sm-9"><?php print $openDate  ?></dd>

            <dt class="col-sm-3">Status:</dt>
            <dd class="col-sm-9"><?php print $status  ?></dd>

            <dt class="col-sm-3">UserId:</dt>
            <dd class="col-sm-9"><?php print $userId ?></dd>

            <dt class="col-sm-3 text-truncate">Issue Category:</dt>
            <dd class="col-sm-9"><?php print $category?></dd>

            <dt class="col-sm-3 text-truncate">Issue Category:</dt>
            <dd class="col-sm-9"><?php print $subject?></dd>

            <dt class="col-sm-3">Messages:</dt>
            <dd class="col-sm-9" class="ticket-Msg">
                <dl class="row">

                    <?php foreach ($msgList as $msg) :?>
                        <?php if($loggedUserRole == 'client') { ?>
                            <dt class="col-sm-10">Posted:<?php echo $msg->description["date"];?> <?php echo $msg->description["time"];?> User ID:<?php echo $msg->description["userId"] ?>
                            </dt>
                            <dd class="col-sm-8"><?php echo $msg->description; ?></dd>
                        <?php }  ?>
                        <?php if($loggedUserRole == 'support staff') { ?>
                            <dt class="col-sm-10">Posted:<?php echo $msg->description["date"];?> <?php echo $msg->description["time"];?> User ID:<?php echo $msg->description["userId"]; ?>
                            </dt>
                            <dd class="col-sm-8"><?php echo $msg->description; ?></dd>
                        <?php }  ?>
                    <?php endforeach;?>

                </dl>
            </dd>
            </dl>

            <form method="post">
                <div class="col-md-6 mb-3">
                    <label for="message">Your message</label>
                    <textarea class="form-control" rows="5" name="message" placeholder="Here you can enter additional messages for the client."></textarea>
                </div>
                <input type="hidden" name="tId" value="<?php echo $ticketId; ?>">
                <input class="btn btn-primary" type="submit" name="addMessage" value="Add Message" role="button"/>
            </form>
        </div>
    </div>
</div>
</body>
