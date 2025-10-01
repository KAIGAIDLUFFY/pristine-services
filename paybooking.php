<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now - Pristine Cleaning Services</title>
    <link rel="stylesheet" href="styles.css">
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="paybooking.php">Book Now</a></li>
                <li><a href="reviews.html">Reviews</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn">Services</a>
                    <div class="dropdown-content">
                        <a href="residential.html">Residential Services</a>
                        <a href="commercial.html">Commercial Services</a>
                        <a href="Additional Services.html">Additional Services</a>
                        <li><a href="logout.php" class="btn">Logout</a>"></a></li>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
<?php
if (isset($_POST['submit'])) {
    date_default_timezone_set('Africa/Nairobi');

    // Extract form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $service = $_POST['service'] ?? '';
    $price = $_POST['price'] ?? '';
    $date = $_POST['date'] ?? null;

   // Validate inputs
   if (empty($name) || empty($phone) || empty($address) || empty($service)) {
    die("Required fields are missing.");
    }

    // Database connection setup
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "book";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert booking details into the database
    $stmt = $conn->prepare("INSERT INTO `booking payment` (name, email, phone, address, service, price, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepared statement failed: " . $conn->error);
    }
    $stmt->bind_param("sssssss", $name, $email, $phone, $address, $service, $price, $date); 
    $stmt->execute();
    if ($stmt->error) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    if (!empty($service) && !empty($phone)) {
        // Handle M-Pesa specific logic if phone number and service are provided
        $consumerKey = 'nk16Y74eSbTaGQgc9WF8j6FigApqOMWr'; // Fill with your app Consumer Key
        $consumerSecret = '40fD1vRXCq90XFaU'; // Fill with your app Secret

        $BusinessShortCode = '174379';
        $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        $AccountReference = 'Pristine Cleaning Services';
        $TransactionDesc = 'Services Payment';
        $Timestamp = date('YmdHis');
        $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

        // Access token request
        $headers = ['Content-Type:application/json; charset=utf8'];
        $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init($access_token_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($result);
        $access_token = $result->access_token;
        curl_close($curl);

        // Initiate the transaction
        $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];
        $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $curl_post_data = array(
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $Password,
            'Timestamp' => $Timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => intval($price),
            'PartyA' => $phone,
            'PartyB' => $BusinessShortCode,
            'PhoneNumber' => $phone,
            'CallBackURL' => 'https://yourserver.com/callback.php', // Update with your actual callback URL
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc
        );
        $data_string = json_encode($curl_post_data);
        $curl = curl_init($initiate_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);

        // Decode response
        $response = json_decode($curl_response, true);
        curl_close($curl);

        // Check if the request was accepted for processing
        if (isset($response['ResponseCode']) && $response['ResponseCode'] == '0') {            // Successful, process response
            $MerchantRequestID = $response['MerchantRequestID'];
            $CheckoutRequestID = $response['CheckoutRequestID'];
            $ResponseDescription = $response['ResponseDescription'];
            $CustomerMessage = $response['CustomerMessage'];

            // Insert the response into the database
            $stmt = $conn->prepare("INSERT INTO mpesa_responses (MerchantRequestID, CheckoutRequestID, ResponseCode, ResponseDescription, CustomerMessage) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $MerchantRequestID, $CheckoutRequestID, $response['ResponseCode'], $ResponseDescription, $CustomerMessage);
            $stmt->execute();
            if ($stmt->error) {
                die("Execute failed: " . $stmt->error);
            }
            $stmt->close();

            echo "M-Pesa request successful: " . $CustomerMessage;
        } else {
            // Handle error
            echo "M-Pesa request failed: " . $response['errorMessage'];
        }
    }

    // Close the database connection
    $conn->close();
    echo "Payment recorded successfully.";
}
?>

    <script>
        const servicePrices = {
            "Regular Cleaning - Standard Room": 1000,
            "Regular Cleaning - Large Room": 2500,
            "Regular Cleaning - Whole House": 5000,
            "Deep Cleaning - Standard Room": 5000,
            "Deep Cleaning - Large Room": 7500,
            "Deep Cleaning - Whole House": 10000,
            "Carpet Cleaning - Standard Room": 2000,
            "Carpet Cleaning - Large Room": 3500,
            "Carpet Cleaning - Whole House": 9000,
            "Move In Cleaning": 7500,
            "Move Out Cleaning": 9000,
            "Mattress Cleaning - Single Mattress": 1500,
            "Mattress Cleaning - Double Mattress": 2500,
            "Mattress Cleaning - King Size Mattress": 3500,
            "Curtain Cleaning - Standard Curtains": 1000,
            "Curtain Cleaning - Large Curtains": 2000,
            "Curtain Cleaning - Heavy/Drapes": 3000,
            "Tile Cleaning - Standard Room": 2500,
            "Tile Cleaning - Large Room": 4000,
            "Decluttering - Standard Room": 3000,
            "Decluttering - Large Room": 5000,
            "Office Cleaning - Standard Office": 1500,
            "Office Cleaning - Large Office": 3000,
            "Restroom Sanitization - All Restrooms": 1500,
            "Pressure Cleaning - Standard Area": 4000,
            "Pressure Cleaning - Large Area": 7000,
            "Floor Polishing - Standard Room": 3000,
            "Floor Polishing - Large Room": 5000,
            "Rubbish Removal - Standard Load": 2500,
            "Rubbish Removal - Large Load": 5000,
            "Water Damage Restoration - Standard Service": 10000,
            "Water Damage Restoration - Intensive Service": 15000,
            "Gardening - Standard Yard": 4000,
            "Gardening - Large Yard": 7000,
            "Roof Cleaning - Standard Roof": 5000,
            "Roof Cleaning - Large Roof": 8000,
            "Pest Control - Standard Treatment": 3000,
            "Pest Control - Intensive Treatment": 6000,
            "Labour Hire - Per Hour": 500,
            "Labour Hire - Full Day": 3500
        };

        function updatePrice() {
            const serviceSelect = document.getElementById('service');
            const priceInput = document.getElementById('price');
            const selectedService = serviceSelect.value;
            const price = servicePrices[selectedService];
            priceInput.value = price ? `${price} Shillings` : '';
        }

        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').setAttribute('min', today);
        });
    </script>
</head>
<body>
    <section>
        <h1>Book a Service</h1>
        <form action="" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="phone">Phone(254):</label>
            <input type="tel" id="phone" name="phone" required>
            <br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <br>
            <label for="service">Service:</label>
            <select id="service" name="service" onchange="updatePrice()" required>
                <option value="">Select a service</option>
                <optgroup label="Residential Services">
                    <option value="Regular Cleaning - Standard Room">Regular Cleaning - Standard Room</option>
                    <option value="Regular Cleaning - Large Room">Regular Cleaning - Large Room</option>
                    <option value="Regular Cleaning - Whole House">Regular Cleaning - Whole House</option>
                    <option value="Deep Cleaning - Standard Room">Deep Cleaning - Standard Room</option>
                    <option value="Deep Cleaning - Large Room">Deep Cleaning - Large Room</option>
                    <option value="Deep Cleaning - Whole House">Deep Cleaning - Whole House</option>
                    <option value="Carpet Cleaning - Standard Room">Carpet Cleaning - Standard Room</option>
                    <option value="Carpet Cleaning - Large Room">Carpet Cleaning - Large Room</option>
                    <option value="Carpet Cleaning - Whole House">Carpet Cleaning - Whole House</option>
                    <option value="Move In Cleaning">Move In Cleaning</option>
                    <option value="Move Out Cleaning">Move Out Cleaning</option>
                    <option value="Mattress Cleaning - Single Mattress">Mattress Cleaning - Single Mattress</option>
                    <option value="Mattress Cleaning - Double Mattress">Mattress Cleaning - Double Mattress</option>
                    <option value="Mattress Cleaning - King Size Mattress">Mattress Cleaning - King Size Mattress</option>
                    <option value="Curtain Cleaning - Standard Curtains">Curtain Cleaning - Standard Curtains</option>
                    <option value="Curtain Cleaning - Large Curtains">Curtain Cleaning - Large Curtains</option>
                    <option value="Curtain Cleaning - Heavy/Drapes">Curtain Cleaning - Heavy/Drapes</option>
                    <option value="Tile Cleaning - Standard Room">Tile Cleaning - Standard Room</option>
                    <option value="Tile Cleaning - Large Room">Tile Cleaning - Large Room</option>
                    <option value="Decluttering - Standard Room">Decluttering - Standard Room</option>
                    <option value="Decluttering - Large Room">Decluttering - Large Room</option>
                </optgroup>
                <optgroup label="Commercial Services">
                    <option value="Office Cleaning - Standard Office">Office Cleaning - Standard Office</option>
                    <option value="Office Cleaning - Large Office">Office Cleaning - Large Office</option>
                    <option value="Restroom Sanitization - All Restrooms">Restroom Sanitization - All Restrooms</option>
                    <option value="Pressure Cleaning - Standard Area">Pressure Cleaning - Standard Area</option>
                    <option value="Pressure Cleaning - Large Area">Pressure Cleaning - Large Area</option>
                    <option value="Floor Polishing - Standard Room">Floor Polishing - Standard Room</option>
                    <option value="Floor Polishing - Large Room">Floor Polishing - Large Room</option>
                    <option value="Rubbish Removal - Standard Load">Rubbish Removal - Standard Load</option>
                    <option value="Rubbish Removal - Large Load">Rubbish Removal - Large Load</option>
                    <option value="Water Damage Restoration - Standard Service">Water Damage Restoration - Standard Service</option>
                    <option value="Water Damage Restoration - Intensive Service">Water Damage Restoration - Intensive Service</option>
                    <option value="Gardening - Standard Yard">Gardening - Standard Yard</option>
                    <option value="Gardening - Large Yard">Gardening - Large Yard</option>
                </optgroup>
                <optgroup label="Additional Services">
                    <option value="Roof Cleaning - Standard Roof">Roof Cleaning - Standard Roof</option>
                    <option value="Roof Cleaning - Large Roof">Roof Cleaning - Large Roof</option>
                    <option value="Pest Control - Standard Treatment">Pest Control - Standard Treatment</option>
                    <option value="Pest Control - Intensive Treatment">Pest Control - Intensive Treatment</option>
                    <option value="Labour Hire - Per Hour">Labour Hire - Per Hour</option>
                    <option value="Labour Hire - Full Day">Labour Hire - Full Day</option>
                </optgroup>
            </select>
            <br>
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" readonly>
            <br>
            <label for="date">Preferred Date:</label>
            <input type="date" id="date" name="date" required>
            <br>
            <button type="submit" name="submit">Book Now</button>
        </form>
    </section>
    <footer>
        <p>&copy; 2024 Pristine Cleaning Services. All rights reserved.</p>
    </footer>
</body>
</html>
