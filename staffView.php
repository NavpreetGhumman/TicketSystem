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
$tickets = simplexml_load_file("ticket.xml");

foreach ($tickets->children() as $p) {
    $rows .= '<tr>';
    $rows .= '<td>' . $p->attributes()['ticketId'] . '</td>';
    $rows .= '<td>' . $p->openDate . '</td>';
    $rows .= '<td>' . $p->ticketStatus . '</td>';
    $rows .= '<td>' . $p->clientId . '</td>';
    $rows .= "<td><a class='btn btn-primary' name='id' href='staffTicketDetail.php?id={$p->attributes()}'>
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
            <h2>Welcome Admin! <?php echo  $_SESSION['username']; ?></h2>
            <div class="col-12 form-container">
                <form>
                    <div>
                        <div class="ticket-heading"> View all Support Tickets</div>
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