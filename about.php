<?php include_once 'helpers/helper.php'; ?>
<?php subview('header.php'); 
    require 'helpers/init_conn_db.php';                      
	?> 	
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="carousel-container">
        <div class="feedback active">
            <p><strong>Nama Pengguna:</strong> Dimas Satria</p>
            <p><strong>Rating:</strong> 5/5</p>
            <p><strong>Komentar:</strong> Pengalaman memesan tiket sangat mudah dan cepat. Interface yang sederhana membuat saya dapat dengan mudah menemukan penerbangan yang sesuai dengan kebutuhan saya. Pasti akan menggunakan layanan ini lagi!</p>
        </div>
        <div class="feedback">
            <p><strong>Nama Pengguna:</strong> Indah Pratiwi</p>
            <p><strong>Rating:</strong> 4/5</p>
            <p><strong>Komentar:</strong> Sistemnya cukup bagus dan user-friendly. Namun, saya mengalami sedikit kesulitan saat melakukan pembayaran dengan kartu kredit. Mohon diperbaiki untuk ke depannya. Terima kasih!</p>
        </div>
        <div class="feedback">
            <p><strong>Nama Pengguna:</strong> Budi Santoso</p>
            <p><strong>Rating:</strong> 3/5</p>
            <p><strong>Komentar:</strong> Proses pemesanan berjalan lancar, tetapi saya mengalami masalah ketika ingin mengubah jadwal penerbangan. Seharusnya ada fitur yang lebih memudahkan untuk reschedule tanpa perlu menghubungi customer service.</p>
        </div>
        <div class="feedback">
            <p><strong>Nama Pengguna:</strong> Siti Aminah</p>
            <p><strong>Rating:</strong> 4.5/5</p>
            <p><strong>Komentar:</strong> Sangat puas dengan layanan ini. Harga yang ditawarkan kompetitif dan banyak pilihan penerbangan. Satu saran, mungkin bisa ditambahkan fitur untuk membandingkan harga dengan situs lain secara langsung.</p>
        </div>
        <div class="feedback">
            <p><strong>Nama Pengguna:</strong> Andi Kurniawan</p>
            <p><strong>Rating:</strong> 2/5</p>
            <p><strong>Komentar:</strong> Pengalaman saya kurang memuaskan. Website sering mengalami error dan loading lama saat saya mencoba mencari penerbangan. Harap diperbaiki agar pengguna lain tidak mengalami hal yang sama.</p>
        </div>
    </div><br><br>
    <center><h1>Reach Us</h1></center>
    <div class="reachus">
        
        <div class="maps">
            <center><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1016915.2764238906!2d104.02547207812502!3d-5.367255800000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40c535ab10e3fb%3A0xe54e447a49d9e70a!2sGedung%20Ilmu%20Komputer%20Universitas%20Lampung%20(GIK%20Unila)!5e0!3m2!1sid!2sid!4v1718270600969!5m2!1sid!2sid" width="1000" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>        </div></center>
        <div class="contact">
            <div class="callus">
                <h1>Call Us</h1>
                <h2>📞+628-123456789</h2>
            </div>
            <div class="followus">
                <h1>Follow Us</h1>
                <a href="https://instagram.com/naaurasls">
                    <img src="assets/images/instagram (1).png" alt="">
                    Instagram
                </a><br><br>
                <a href="https://x.com/officialjkt48">
                    <img src="assets/images/twitter.png" alt="">
                    Twitter
                </a><br><br>
                <a href="https://facebook.com/officialjkt48">
                    <img src="assets/images/facebook.png" alt="">
                    Facebook
                </a>
            </div>
        </div>
    </div> <br><br><br>
    <footer class="bg-dark text-white text-center text-lg-start">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
            <h5 class="text-light text-center p-0 brand mt-2">
				<img src="assets/images/airtic.png" 
					height="40px" width="40px" alt="">				
			Online Flight Booking</h5>		
                <p>One Stop Ticket Solution</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Tautan</h5>
                <ul class="list-unstyled mb-0">
                    <li><a href="#!" class="text-white">Tentang Kami</a></li>
                    <li><a href="#!" class="text-white">Layanan</a></li>
                    <li><a href="#!" class="text-white">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Lainnya</h5>
                <ul class="list-unstyled mb-0">
                    <li><a href="#!" class="text-white">Kebijakan Privasi</a></li>
                    <li><a href="#!" class="text-white">Syarat dan Ketentuan</a></li>
                    <li><a href="#!" class="text-white">Bantuan</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        &copy; 2024 Perusahaan Anda. All rights reserved.
    </div>
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