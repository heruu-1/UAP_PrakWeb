<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional CSS for the contact-info box */
        .contact-info {
            border: 2px solid #ccc; /* Border style */
            padding: 20px; /* Padding inside the box */
            background-color: #f9f9f9; /* Background color */
        }
    </style>
</head>
<body>
    <?php include_once 'helpers/helper.php'; ?>
    <?php subview('header.php'); ?>
    <?php require 'helpers/init_conn_db.php'; ?>
    
    <div class="carousel-container">
        <?php
        $query = "SELECT email, q1, q2, q3, rate FROM feedback ORDER BY q3 DESC";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<div class="feedback">';
                echo '<p><strong>Rating:</strong> ' . $row['rate'] . '/5</p>';
                echo '<p><strong>Email:</strong> ' . $row['email'] . '</p>';
                echo '<p><strong>First Impression:</strong> ' . $row['q1'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No feedback available.</p>';
        }
        ?>
    </div><br><br>
    
    <div class="container text-center my-5">
        <h1>Reach Us</h1>
        <div class="row mt-4">
            <div class="col-md-8 mb-4">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1016915.2764238906!2d104.02547207812502!3d-5.367255800000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40c535ab10e3fb%3A0xe54e447a49d9e70a!2sGedung%20Ilmu%20Komputer%20Universitas%20Lampung%20(GIK%20Unila)!5e0!3m2!1sid!2sid!4v1718270600969!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div class="col-md-4 text-left">
                <div class="contact-info">
                    <h2>Call Us</h2>
                    <h3>📞 +628-123456789</h3>
                    <h2 class="mt-4">Follow Us</h2>
                    <p>
                        <a href="https://instagram.com/naaurasls" class="text-decoration-none">
                            <img src="assets/images/instagram (1).png" alt="" width="30" height="30"> Instagram
                        </a>
                    </p>
                    <p>
                        <a href="https://x.com/officialjkt48" class="text-decoration-none">
                            <img src="assets/images/twitter.png" alt="" width="30" height="30"> Twitter
                        </a>
                    </p>
                    <p>
                        <a href="https://facebook.com/officialjkt48" class="text-decoration-none">
                            <img src="assets/images/facebook.png" alt="" width="30" height="30"> Facebook
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <em><h5 class="text-light text-center p-0 brand mt-2">
            <img src="assets/images/airtic.png" height="40px" width="40px" alt="">				
        Online Flight Booking</h5></em>
    </footer>
    
    <script>
        let currentIndex = 0;
        const feedbacks = document.querySelectorAll('.feedback');

        function showNextFeedback() {
            feedbacks[currentIndex].classList.remove('active');
            feedbacks[currentIndex].classList.add('previous');
            currentIndex = (currentIndex + 1) % feedbacks.length;
            feedbacks[currentIndex].classList.remove('previous');
            feedbacks[currentIndex].classList.add('active');
        }

        setInterval(showNextFeedback, 3000);
    </script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
