<?php require_once 'config/db.php';
require_once 'includes/track_view.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/seo_tags.php'; ?>

    <!-- Favicon -->
     <link href="img/logo.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
     <?php require 'header.php'; ?>

    <!-- Header Start -->
    <div class="container-fluid page-header">
        <div class="container">
            <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 400px">
                <h3 class="display-4 text-white text-uppercase">Contact</h3>
                <div class="d-inline-flex text-white">
                    <p class="m-0 text-uppercase"><a class="text-white" href="">Home</a></p>
                    <i class="fa fa-angle-double-right pt-1 px-3"></i>
                    <p class="m-0 text-uppercase">Contact</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


    


    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="text-center mb-3 pb-3">
                <h6 class="text-primary text-uppercase" style="letter-spacing: 5px; color: #F4A621 !important; ">Contact</h6>
                <h1>Contact For Any Query</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-white" style="padding: 30px;">
                        <h5 class="text-center mb-4">Receive messages instantly with our PHP and Ajax contact form - available in the <a href="https://htmlcodex.com/downloading/?item=1518">Pro Version</a> only.</h5>
                        <form>
                            <div class="form-row">
                                <div class="col-sm-6 mb-3">
                                    <input type="text" class="form-control p-4" placeholder="Your Name" />
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <input type="email" class="form-control p-4" placeholder="Your Email" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control p-4" placeholder="Subject" />
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control py-3 px-4" rows="5" placeholder="Message"></textarea>
                            </div>
                            <div class="text-center">
                                <button class="btn py-3 px-4" type="submit" style="background-color: #F4A621; color: white;">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

    <?php require 'footer.php'; ?>



    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-lg-square back-to-top" style="background-color: #F4A621; color: white;"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>