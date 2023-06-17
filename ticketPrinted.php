<?php
session_start();
$loggedIn = isset($_SESSION['username']);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['passengerId']) && isset($_GET['ticketNo'])) {
    $passengerId = $_GET['passengerId'];
    $ticketNo = $_GET['ticketNo'];
    // Rest of your code
} else {
    // Handle the case when the parameters are not provided
    echo "Passenger ID or ticket number not provided";
}
// Fetch ticket data from the database based on the ticket number
$sql = "SELECT
			p.passenger_fullname, p.passenger_id, 
			t.ticket_type, t.ticket_class, t.ticket_price, t.ticket_no,
			f.departure, f.destination,f.flight_no,
			a.airline_name,s.airport_id, ar.airport_name, ar.airport_city,
			ar.airport_country, a.airline_no,
			DATE_FORMAT(flight_date, '%d/%m/%Y') AS departure_date, 
			DATE_FORMAT(fd.flight_date, '%H:%i') AS departure_time
            FROM Transactions tr
            INNER JOIN Ticket t ON tr.ticket_no = t.ticket_no
            INNER JOIN Flight f ON t.flight_no = f.flight_no
            INNER JOIN Airline a ON f.airline_no = a.airline_no
            INNER JOIN serve s ON a.airline_no = s.airline_no
            INNER JOIN airport ar ON ar.airport_id = s.airport_id
            INNER JOIN Passengers p ON tr.passenger_id = p.passenger_id
            INNER JOIN flightdate fd ON f.flight_no = fd.flight_no
		WHERE tr.passenger_id = $passengerId AND t.ticket_no = '$ticketNo'
		GROUP BY t.ticket_type, t.ticket_class, t.ticket_price, f.departure,
			f.destination, a.airline_name, p.passenger_fullname, t.ticket_no";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $ticket = $result->fetch_assoc();

	$airlineName = $ticket['airline_name']; 
	$airlineID = $ticket['airline_no']; 
	$airportName = $ticket['airport_name']; 
	$airportID = $ticket['airport_id'];
	$airportCity = $ticket['airport_city'];
	$airportCountry = $ticket['airport_country'];
    $passengerId = $ticket['passenger_id'];
    $fullName = $ticket["passenger_fullname"]; 
    $departure = $ticket["departure"];
    $arrival = $ticket["destination"];
    $dateOfDeparture = $ticket['departure_date'];
	$timeOfDeparture = $ticket['departure_time']; 
    $returnOrOneWay = $ticket["ticket_type"];
    $ticketClass = $ticket["ticket_class"];
    $totalFareAmount = "$" . $ticket["ticket_price"] ;
	$flightID = $ticket['flight_no']; 
} else {
    echo "Ticket not found";
}
?>

<head>
  <link rel="stylesheet" href="http://localhost/Flight-Booking-System/css/ticketStyle.css">
</head>
<div class="ticket">
	<div id="banner">
		<div id="topbanner"></div>
		<span id="mainbanner">
			 

			<img src="https://lukw4l.de/utils/media/assets/flightticket/plane_logo.png">
			<?php echo $airlineName;?>
		</span>
		<span id="tearoffbanner">
			<img src="https://lukw4l.de/utils/media/assets/flightticket/plane_logo.png">
			<?php echo $airlineName;?>
		</span>
	</div>
	<div id="barcode">
	<?php echo $ticketNo; ?>
	</div>
	<div id="data">
		<div id="maindata">
			<div class="box">
				<span class="header">
				<?php echo $airportName.','.$airportCountry; ?> <br>
				<?php echo $passengerId; ?>
				</span>
				<span class="body">
				 
				</span>
			</div>
			<div class="box">
				<span class="header">
				<?php echo $ticketNo;  ?>
				</span>
				<span class="body">
				<?php echo substr($airlineName, 0, 4).substr($airportID, -2); ?>
				</span>
			</div>
			<div class="box">
				<span class="header">
					From
				</span>
				<span class="body">
				<?php echo $departure; ?>
				</span>
			</div>
			<div class="box">
				<span class="header">
					Date
				</span>
				<span class="body">
				<?php echo $dateOfDeparture; ?>
				</span>
			</div>
			<div class="box">
				<span class="header">
					To
				</span>
				<span class="body">
				<?php echo $arrival; ?>
				</span>
			</div>
			<div class="box">
			</div>
			<div class="box">
				<span class="header">
					Gate
				</span>
				<span class="body">
					B-10
				</span>
			</div>
			<div class="box boarding">
				<span class="header">
					Boarding Time
				</span>
				<span class="body">
				<?php echo $timeOfDeparture; ?>
				</span>
			</div>

			<div id="tearoffdata">
				<div class="box">
					<span class="header">
						Passenger Name
					</span>
					<span class="body">
					<?php echo $fullName; ?>
					</span>
				</div>
				<div class="box">
					<span class="header">
						Flight Number
					</span>
					<span class="body">
					<?php echo $flightID; ?>
					</span>
				</div>
				<div class="box">
					<span class="header">
						Date
					</span>
					<span class="body">
					<?php echo $dateOfDeparture; ?>
					</span>
				</div>
				<div class="box">
					<span class="header">
						Gate
					</span>
					<span class="body">
						B-10
					</span>
				</div>
				<div class="box seat">
					<span class="header">
						Seat
					</span>
					<span class="body">
						<b>23F</b>
					</span>
				</div>
			</div>
		</div>

		<div id="holes">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>
	</div>
</div> 

