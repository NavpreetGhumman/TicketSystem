<?php

session_start();
$loggedUserEmail =  $_SESSION['loggedUserEmail'];
$userIdLogged = $_SESSION['loggedUserId'];
$loggedUserRole = $_SESSION['loggedUserRole'];

if(!isset($_SESSION['username']))
{
    header('Location: login.php');
}

date_default_timezone_set("America/Toronto");

$rows = "";
$category = "";


if(isset($_POST['submitTicket'])) {
    $tickets = simplexml_load_file("ticket.xml");
    $ticket = $tickets->xpath("/tickets/ticket/clientId[text()=$userIdLogged]/parent::*");
    $lastTicket = $tickets->xpath("/tickets/ticket[last()]");
    $lastTicketId = $lastTicket[0]->attributes()['ticketId'];
    $int = intval($lastTicketId)+1;
    $lastId = "000".$int;

    // storing form input
    $Date = date('Y-m-d\Th:i:s');
    $category = $_POST['category'];
    $clientId = $userIdLogged;
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $ticketDate = date("Y-m-d");
    $ticketTime = date("h:i:s");

    $ticket = $tickets->addChild('ticket');
    $ticket->addAttribute('ticketId', $lastId);
    $ticket->addChild('openDate', $Date);
    $ticket->addChild('ticketStatus', 'Open');
    $ticket->addChild('ticketCategory',$category);
    $ticket->addChild('ticketSubject', $subject);
    $ticket->addChild('clientId', $clientId);
    $ticketMessage = $ticket->addChild('ticketMessage');
    $description = $ticketMessage->addChild('description', value: $message);
    $description->addAttribute('userId', $clientId);
    $description->addAttribute('date', $ticketDate);
    $description->addAttribute('time', $ticketTime);

    $tickets->saveXML("ticket.xml");
}

$tickets = simplexml_load_file("ticket.xml");
$ticket = $tickets->xpath("/tickets/ticket/clientId[text()=$userIdLogged]/parent::*");



foreach ($ticket as $p) {
    $rows .= '<tr>';
    $rows .= '<td>'.$p->attributes()['ticketId'].'</td>';
    $rows .= '<td>'.$p->openDate.'</td>';
    $rows .= '<td>'.$p->ticketStatus.'</td>';
    $rows .= '<td>'.$p->clientId.'</td>';
    $rows .=  "<td><a class='btn btn-primary' name='id' href='clientTicketDetail.php?id={$p->attributes()}'>
                        View and Add Message</a></td>";
    $rows .= '</tr>';
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
        <div class="ticket-listing">
            <h2>Welcome! <?php echo  $_SESSION['username']; ?></h2>
            <div class="ticket-heading">Submit a Support Ticket</div>

            <div class="col-12 form-container">
                <form method="post">
                    <label for="form-options">Category:</label>
                    <select class="form-control" id="form-options" name="category" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="Return">Returns</option>
                        <option value="Search Item">Search Item</option>
                        <option value="Shipping">Shipping</option>
                        <option value="General">General</option>
                        <option value="Payment">Payment</option>
                    <option value="Account">Account</option>
                    </select>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-subject">Subject</label>
                        <input type="text" class="form-control" id="form-subject" name="subject">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="message">Message</label>
                        <textarea class="form-control" rows="5" name="message" placeholder="Here you can enter your message"></textarea>
                    </div>
                    <div class="col-2">
                        <input type="submit" value="Submit Ticket" name="submitTicket" class="btn btn-block btn-primary">
                    </div>
                    <div>
                        <div class="ticket-heading">Support Ticket</div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">Ticket ID</th>
                                <th scope="col">Open Date</th>
                                <th scope="col">Ticket Status</th>
                                <th scope="col">User ID</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php  print $rows; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>